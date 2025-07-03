from fastapi import APIRouter, Depends, HTTPException, status, Header
from sqlalchemy.orm import Session
from typing import List, Optional
import crud
import schemas
import models
from database import get_db
from utils import decrypt_data, mask_card_number

router = APIRouter()

# Store master password in memory (in production, use proper session management)
current_master_password = None

def get_current_master_password(x_master_password: Optional[str] = Header(None)):
    global current_master_password
    
    # First try to get from header
    if x_master_password:
        return x_master_password
    
    # Fall back to global variable
    if not current_master_password:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Master password required. Please authenticate first."
        )
    return current_master_password

# Authentication
@router.post("/auth/login", response_model=schemas.Token ,tags=["auth"])
async def login(request: schemas.MasterPasswordRequest):
    global current_master_password
    # In production, verify master password against a hashed version
    current_master_password = request.master_password
    return {"access_token": "authenticated", "token_type": "bearer"}

@router.post("/auth/logout",tags=["auth"])
async def logout():
    global current_master_password
    current_master_password = None
    return {"message": "Logged out successfully"}

# Category routes
@router.get("/categories", response_model=List[schemas.Category],tags=["categories"])
def read_categories(skip: int = 0, limit: int = 100, db: Session = Depends(get_db)):
    categories = crud.get_categories(db, skip=skip, limit=limit)
    return categories

@router.post("/categories", response_model=schemas.Category,tags=["categories"])
def create_category(category: schemas.CategoryCreate, db: Session = Depends(get_db)):
    db_category = crud.get_category_by_name(db, name=category.name)
    if db_category:
        raise HTTPException(status_code=400, detail="Category already exists")
    return crud.create_category(db=db, category=category)

@router.get("/categories/{category_id}", response_model=schemas.Category,tags=["categories"])
def read_category(category_id: int, db: Session = Depends(get_db)):
    db_category = crud.get_category_by_id(db, category_id=category_id)
    if db_category is None:
        raise HTTPException(status_code=404, detail="Category not found")
    return db_category

@router.put("/categories/{category_id}", response_model=schemas.Category,tags=["categories"])
def update_category(category_id: int, category: schemas.CategoryUpdate, db: Session = Depends(get_db)):
    db_category = crud.update_category(db, category_id=category_id, category=category)
    if db_category is None:
        raise HTTPException(status_code=404, detail="Category not found")
    return db_category

@router.delete("/categories/{category_id}",tags=["categories"])
def delete_category(category_id: int, db: Session = Depends(get_db)):
    success = crud.delete_category(db, category_id=category_id)
    if not success:
        raise HTTPException(status_code=404, detail="Category not found")
    return {"message": "Category deleted successfully"}

# Credential routes
@router.get("/credentials", response_model=List[schemas.CredentialResponse],tags=["credentials"])
def read_credentials(skip: int = 0, limit: int = 100, db: Session = Depends(get_db)):
    credentials = crud.get_credentials(db, skip=skip, limit=limit)
    return credentials

@router.post("/credentials", response_model=schemas.CredentialResponse,tags=["credentials"])
def create_credential(
    credential: schemas.CredentialCreate,
    db: Session = Depends(get_db),
    master_password: str = Depends(get_current_master_password)
):
    # Verify category exists
    category = crud.get_category_by_id(db, credential.category_id)
    if not category:
        raise HTTPException(status_code=400, detail="Category not found")
    
    return crud.create_credential(db=db, credential=credential, master_password=master_password)

@router.get("/credentials/{credential_id}", response_model=schemas.CredentialWithPassword,tags=["credentials"])
def read_credential(
    credential_id: int,
    db: Session = Depends(get_db),
    master_password: str = Depends(get_current_master_password)
):
    db_credential = crud.get_credential_by_id(db, credential_id=credential_id)
    if db_credential is None:
        raise HTTPException(status_code=404, detail="Credential not found")
    
    # Decrypt password
    try:
        decrypted_password = decrypt_data(db_credential.password_encrypted, master_password)
        # Update last used
        crud.update_credential_last_used(db, credential_id)
        
        # Convert to response model
        response = schemas.CredentialWithPassword(
            id=db_credential.id,
            category_id=db_credential.category_id,
            service_name=db_credential.service_name,
            username=db_credential.username,
            email=db_credential.email,
            password=decrypted_password,
            website_url=db_credential.website_url,
            notes=db_credential.notes,
            is_favorite=db_credential.is_favorite,
            created_at=db_credential.created_at,
            updated_at=db_credential.updated_at,
            last_used=db_credential.last_used,
            category=db_credential.category
        )
        return response
    except Exception as e:
        raise HTTPException(status_code=400, detail="Failed to decrypt password. Check master password.")

@router.put("/credentials/{credential_id}", response_model=schemas.CredentialResponse,tags=["credentials"])
def update_credential(
    credential_id: int,
    credential: schemas.CredentialUpdate,
    db: Session = Depends(get_db),
    master_password: str = Depends(get_current_master_password)
):
    db_credential = crud.update_credential(db, credential_id=credential_id, credential=credential, master_password=master_password)
    if db_credential is None:
        raise HTTPException(status_code=404, detail="Credential not found")
    return db_credential

@router.delete("/credentials/{credential_id}",tags=["credentials"])
def delete_credential(credential_id: int, db: Session = Depends(get_db)):
    success = crud.delete_credential(db, credential_id=credential_id)
    if not success:
        raise HTTPException(status_code=404, detail="Credential not found")
    return {"message": "Credential deleted successfully"}

@router.get("/credentials/search/{query}", response_model=List[schemas.CredentialResponse],tags=["credentials"])
def search_credentials(query: str, db: Session = Depends(get_db)):
    credentials = crud.search_credentials(db, query=query)
    return credentials

@router.get("/categories/{category_id}/credentials", response_model=List[schemas.CredentialResponse],tags=["credentials"])
def read_credentials_by_category(category_id: int, db: Session = Depends(get_db)):
    credentials = crud.get_credentials_by_category(db, category_id=category_id)
    return credentials

# Credit Card routes
@router.get("/credit-cards", response_model=List[schemas.CreditCardResponse],tags=["credit-cards"])
def read_credit_cards(
    skip: int = 0, 
    limit: int = 100, 
    db: Session = Depends(get_db),
    master_password: str = Depends(get_current_master_password)
):
    cards = crud.get_credit_cards(db, skip=skip, limit=limit)
    response_cards = []
    for card in cards:
        try:
            # Decrypt card number for masking
            decrypted_card_number = decrypt_data(card.card_number_encrypted, master_password)
            masked_card_number = mask_card_number(decrypted_card_number)
            
            card_response = schemas.CreditCardResponse(
                id=card.id,
                card_name=card.card_name,
                cardholder_name=card.cardholder_name,
                expiry_month=card.expiry_month,
                expiry_year=card.expiry_year,
                bank_name=card.bank_name,
                card_type=card.card_type,
                billing_address=card.billing_address,
                notes=card.notes,
                created_at=card.created_at,
                updated_at=card.updated_at,
                card_number_masked=masked_card_number
            )
            response_cards.append(card_response)
        except Exception:
            # Skip cards that can't be decrypted
            continue
    return response_cards

@router.post("/credit-cards", response_model=schemas.CreditCardResponse,tags=["credit-cards"])
def create_credit_card(
    card: schemas.CreditCardCreate,
    db: Session = Depends(get_db),
    master_password: str = Depends(get_current_master_password)
):
    db_card = crud.create_credit_card(db=db, card=card, master_password=master_password)
    
    # Return response with masked card number
    decrypted_card_number = decrypt_data(db_card.card_number_encrypted, master_password)
    masked_card_number = mask_card_number(decrypted_card_number)
    
    return schemas.CreditCardResponse(
        id=db_card.id,
        card_name=db_card.card_name,
        cardholder_name=db_card.cardholder_name,
        expiry_month=db_card.expiry_month,
        expiry_year=db_card.expiry_year,
        bank_name=db_card.bank_name,
        card_type=db_card.card_type,
        billing_address=db_card.billing_address,
        notes=db_card.notes,
        created_at=db_card.created_at,
        updated_at=db_card.updated_at,
        card_number_masked=masked_card_number
    )

@router.get("/credit-cards/{card_id}", response_model=schemas.CreditCardWithDetails,tags=["credit-cards"])
def read_credit_card(
    card_id: int,
    db: Session = Depends(get_db),
    master_password: str = Depends(get_current_master_password)
):
    db_card = crud.get_credit_card_by_id(db, card_id=card_id)
    if db_card is None:
        raise HTTPException(status_code=404, detail="Credit card not found")
    
    try:
        # Decrypt sensitive data
        decrypted_card_number = decrypt_data(db_card.card_number_encrypted, master_password)
        decrypted_cvv = decrypt_data(db_card.cvv_encrypted, master_password)
        masked_card_number = mask_card_number(decrypted_card_number)
        
        return schemas.CreditCardWithDetails(
            id=db_card.id,
            card_name=db_card.card_name,
            cardholder_name=db_card.cardholder_name,
            expiry_month=db_card.expiry_month,
            expiry_year=db_card.expiry_year,
            bank_name=db_card.bank_name,
            card_type=db_card.card_type,
            billing_address=db_card.billing_address,
            notes=db_card.notes,
            created_at=db_card.created_at,
            updated_at=db_card.updated_at,
            card_number_masked=masked_card_number,
            card_number=decrypted_card_number,
            cvv=decrypted_cvv
        )
    except Exception as e:
        raise HTTPException(status_code=400, detail="Failed to decrypt card data. Check master password.")

@router.put("/credit-cards/{card_id}", response_model=schemas.CreditCardResponse,tags=["credit-cards"])
def update_credit_card(
    card_id: int,
    card: schemas.CreditCardUpdate,
    db: Session = Depends(get_db),
    master_password: str = Depends(get_current_master_password)
):
    db_card = crud.update_credit_card(db, card_id=card_id, card=card, master_password=master_password)
    if db_card is None:
        raise HTTPException(status_code=404, detail="Credit card not found")
    
    # Return response with masked card number
    decrypted_card_number = decrypt_data(db_card.card_number_encrypted, master_password)
    masked_card_number = mask_card_number(decrypted_card_number)
    
    return schemas.CreditCardResponse(
        id=db_card.id,
        card_name=db_card.card_name,
        cardholder_name=db_card.cardholder_name,
        expiry_month=db_card.expiry_month,
        expiry_year=db_card.expiry_year,
        bank_name=db_card.bank_name,
        card_type=db_card.card_type,
        billing_address=db_card.billing_address,
        notes=db_card.notes,
        created_at=db_card.created_at,
        updated_at=db_card.updated_at,
        card_number_masked=masked_card_number
    )

@router.delete("/credit-cards/{card_id}",tags=["credit-cards"])
def delete_credit_card(card_id: int, db: Session = Depends(get_db)):
    success = crud.delete_credit_card(db, card_id=card_id)
    if not success:
        raise HTTPException(status_code=404, detail="Credit card not found")
    return {"message": "Credit card deleted successfully"}

# Secure Note routes
@router.get("/secure-notes", response_model=List[schemas.SecureNoteResponse],tags=["secure-notes"])
def read_secure_notes(skip: int = 0, limit: int = 100, db: Session = Depends(get_db)):
    notes = crud.get_secure_notes(db, skip=skip, limit=limit)
    return notes

@router.post("/secure-notes", response_model=schemas.SecureNoteResponse,tags=["secure-notes"])
def create_secure_note(
    note: schemas.SecureNoteCreate,
    db: Session = Depends(get_db),
    master_password: str = Depends(get_current_master_password)
):
    return crud.create_secure_note(db=db, note=note, master_password=master_password)

@router.get("/secure-notes/{note_id}", response_model=schemas.SecureNoteWithContent,tags=["secure-notes"])
def read_secure_note(
    note_id: int,
    db: Session = Depends(get_db),
    master_password: str = Depends(get_current_master_password)
):
    db_note = crud.get_secure_note_by_id(db, note_id=note_id)
    if db_note is None:
        raise HTTPException(status_code=404, detail="Secure note not found")
    
    try:
        # Decrypt content
        decrypted_content = decrypt_data(db_note.content_encrypted, master_password)
        
        return schemas.SecureNoteWithContent(
            id=db_note.id,
            title=db_note.title,
            content=decrypted_content,
            category=db_note.category,
            tags=db_note.tags,
            created_at=db_note.created_at,
            updated_at=db_note.updated_at
        )
    except Exception as e:
        raise HTTPException(status_code=400, detail="Failed to decrypt note content. Check master password.")

@router.put("/secure-notes/{note_id}", response_model=schemas.SecureNoteResponse,tags=["secure-notes"])
def update_secure_note(
    note_id: int,
    note: schemas.SecureNoteUpdate,
    db: Session = Depends(get_db),
    master_password: str = Depends(get_current_master_password)
):
    db_note = crud.update_secure_note(db, note_id=note_id, note=note, master_password=master_password)
    if db_note is None:
        raise HTTPException(status_code=404, detail="Secure note not found")
    return db_note

@router.delete("/secure-notes/{note_id}",tags=["secure-notes"])
def delete_secure_note(note_id: int, db: Session = Depends(get_db)):
    success = crud.delete_secure_note(db, note_id=note_id)
    if not success:
        raise HTTPException(status_code=404, detail="Secure note not found")
    return {"message": "Secure note deleted successfully"}
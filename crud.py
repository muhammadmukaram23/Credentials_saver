from sqlalchemy.orm import Session
from sqlalchemy import and_, or_
from typing import List, Optional
from datetime import datetime
import models
import schemas
from utils import encrypt_data, decrypt_data

# Category CRUD
def get_categories(db: Session, skip: int = 0, limit: int = 100) -> List[models.Category]:
    return db.query(models.Category).offset(skip).limit(limit).all()

def get_category_by_id(db: Session, category_id: int) -> Optional[models.Category]:
    return db.query(models.Category).filter(models.Category.id == category_id).first()

def get_category_by_name(db: Session, name: str) -> Optional[models.Category]:
    return db.query(models.Category).filter(models.Category.name == name).first()

def create_category(db: Session, category: schemas.CategoryCreate) -> models.Category:
    db_category = models.Category(**category.dict())
    db.add(db_category)
    db.commit()
    db.refresh(db_category)
    return db_category

def update_category(db: Session, category_id: int, category: schemas.CategoryUpdate) -> Optional[models.Category]:
    db_category = get_category_by_id(db, category_id)
    if db_category:
        for key, value in category.dict(exclude_unset=True).items():
            setattr(db_category, key, value)
        db.commit()
        db.refresh(db_category)
    return db_category

def delete_category(db: Session, category_id: int) -> bool:
    db_category = get_category_by_id(db, category_id)
    if db_category:
        db.delete(db_category)
        db.commit()
        return True
    return False

# Credential CRUD
def get_credentials(db: Session, skip: int = 0, limit: int = 100) -> List[models.Credential]:
    return db.query(models.Credential).offset(skip).limit(limit).all()

def get_credential_by_id(db: Session, credential_id: int) -> Optional[models.Credential]:
    return db.query(models.Credential).filter(models.Credential.id == credential_id).first()

def get_credentials_by_category(db: Session, category_id: int) -> List[models.Credential]:
    return db.query(models.Credential).filter(models.Credential.category_id == category_id).all()

def search_credentials(db: Session, query: str) -> List[models.Credential]:
    return db.query(models.Credential).filter(
        or_(
            models.Credential.service_name.ilike(f"%{query}%"),
            models.Credential.username.ilike(f"%{query}%"),
            models.Credential.email.ilike(f"%{query}%")
        )
    ).all()

def create_credential(db: Session, credential: schemas.CredentialCreate, master_password: str) -> models.Credential:
    # Save old password to history if updating
    encrypted_password = encrypt_data(credential.password, master_password)
    
    db_credential = models.Credential(
        category_id=credential.category_id,
        service_name=credential.service_name,
        username=credential.username,
        email=credential.email,
        password_encrypted=encrypted_password,
        website_url=credential.website_url,
        notes=credential.notes,
        is_favorite=credential.is_favorite
    )
    
    db.add(db_credential)
    db.commit()
    db.refresh(db_credential)
    return db_credential

def update_credential(db: Session, credential_id: int, credential: schemas.CredentialUpdate, master_password: str) -> Optional[models.Credential]:
    db_credential = get_credential_by_id(db, credential_id)
    if db_credential:
        # Save old password to history if password is being updated
        if credential.password:
            old_password_history = models.PasswordHistory(
                credential_id=credential_id,
                old_password_encrypted=db_credential.password_encrypted
            )
            db.add(old_password_history)
            
            # Encrypt new password
            encrypted_password = encrypt_data(credential.password, master_password)
            setattr(db_credential, 'password_encrypted', encrypted_password)
        
        # Update other fields
        for key, value in credential.dict(exclude_unset=True, exclude={'password'}).items():
            setattr(db_credential, key, value)
        
        db.commit()
        db.refresh(db_credential)
    return db_credential

def delete_credential(db: Session, credential_id: int) -> bool:
    db_credential = get_credential_by_id(db, credential_id)
    if db_credential:
        db.delete(db_credential)
        db.commit()
        return True
    return False

def update_credential_last_used(db: Session, credential_id: int):
    db_credential = get_credential_by_id(db, credential_id)
    if db_credential:
        db_credential.last_used = datetime.utcnow()
        db.commit()

# Credit Card CRUD
def get_credit_cards(db: Session, skip: int = 0, limit: int = 100) -> List[models.CreditCard]:
    return db.query(models.CreditCard).offset(skip).limit(limit).all()

def get_credit_card_by_id(db: Session, card_id: int) -> Optional[models.CreditCard]:
    return db.query(models.CreditCard).filter(models.CreditCard.id == card_id).first()

def create_credit_card(db: Session, card: schemas.CreditCardCreate, master_password: str) -> models.CreditCard:
    encrypted_card_number = encrypt_data(card.card_number, master_password)
    encrypted_cvv = encrypt_data(card.cvv, master_password)
    
    db_card = models.CreditCard(
        card_name=card.card_name,
        cardholder_name=card.cardholder_name,
        card_number_encrypted=encrypted_card_number,
        expiry_month=card.expiry_month,
        expiry_year=card.expiry_year,
        cvv_encrypted=encrypted_cvv,
        bank_name=card.bank_name,
        card_type=card.card_type,
        billing_address=card.billing_address,
        notes=card.notes
    )
    
    db.add(db_card)
    db.commit()
    db.refresh(db_card)
    return db_card

def update_credit_card(db: Session, card_id: int, card: schemas.CreditCardUpdate, master_password: str) -> Optional[models.CreditCard]:
    db_card = get_credit_card_by_id(db, card_id)
    if db_card:
        update_data = card.dict(exclude_unset=True)
        
        # Encrypt sensitive fields if provided
        if 'card_number' in update_data:
            update_data['card_number_encrypted'] = encrypt_data(update_data.pop('card_number'), master_password)
        if 'cvv' in update_data:
            update_data['cvv_encrypted'] = encrypt_data(update_data.pop('cvv'), master_password)
        
        for key, value in update_data.items():
            setattr(db_card, key, value)
        
        db.commit()
        db.refresh(db_card)
    return db_card

def delete_credit_card(db: Session, card_id: int) -> bool:
    db_card = get_credit_card_by_id(db, card_id)
    if db_card:
        db.delete(db_card)
        db.commit()
        return True
    return False

# Secure Note CRUD
def get_secure_notes(db: Session, skip: int = 0, limit: int = 100) -> List[models.SecureNote]:
    return db.query(models.SecureNote).offset(skip).limit(limit).all()

def get_secure_note_by_id(db: Session, note_id: int) -> Optional[models.SecureNote]:
    return db.query(models.SecureNote).filter(models.SecureNote.id == note_id).first()

def create_secure_note(db: Session, note: schemas.SecureNoteCreate, master_password: str) -> models.SecureNote:
    encrypted_content = encrypt_data(note.content, master_password)
    
    db_note = models.SecureNote(
        title=note.title,
        content_encrypted=encrypted_content,
        category=note.category,
        tags=note.tags
    )
    
    db.add(db_note)
    db.commit()
    db.refresh(db_note)
    return db_note

def update_secure_note(db: Session, note_id: int, note: schemas.SecureNoteUpdate, master_password: str) -> Optional[models.SecureNote]:
    db_note = get_secure_note_by_id(db, note_id)
    if db_note:
        update_data = note.dict(exclude_unset=True)
        
        # Encrypt content if provided
        if 'content' in update_data:
            update_data['content_encrypted'] = encrypt_data(update_data.pop('content'), master_password)
        
        for key, value in update_data.items():
            setattr(db_note, key, value)
        
        db.commit()
        db.refresh(db_note)
    return db_note

def delete_secure_note(db: Session, note_id: int) -> bool:
    db_note = get_secure_note_by_id(db, note_id)
    if db_note:
        db.delete(db_note)
        db.commit()
        return True
    return False
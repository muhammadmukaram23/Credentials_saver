from pydantic import BaseModel, EmailStr, Field
from typing import Optional, List
from datetime import datetime
from enum import Enum

class CardTypeEnum(str, Enum):
    visa = "visa"
    mastercard = "mastercard"
    amex = "amex"
    discover = "discover"
    other = "other"

# Category Schemas
class CategoryBase(BaseModel):
    name: str = Field(..., max_length=100)
    description: Optional[str] = None
    icon: Optional[str] = Field(None, max_length=50)

class CategoryCreate(CategoryBase):
    pass

class CategoryUpdate(BaseModel):
    name: Optional[str] = Field(None, max_length=100)
    description: Optional[str] = None
    icon: Optional[str] = Field(None, max_length=50)

class Category(CategoryBase):
    id: int
    created_at: datetime
    updated_at: datetime

    class Config:
        from_attributes = True

# Credential Schemas
class CredentialBase(BaseModel):
    service_name: str = Field(..., max_length=255)
    username: Optional[str] = Field(None, max_length=255)
    email: Optional[EmailStr] = None
    website_url: Optional[str] = Field(None, max_length=500)
    notes: Optional[str] = None
    is_favorite: bool = False

class CredentialCreate(CredentialBase):
    category_id: int
    password: str = Field(..., min_length=1)

class CredentialUpdate(BaseModel):
    category_id: Optional[int] = None
    service_name: Optional[str] = Field(None, max_length=255)
    username: Optional[str] = Field(None, max_length=255)
    email: Optional[EmailStr] = None
    password: Optional[str] = None
    website_url: Optional[str] = Field(None, max_length=500)
    notes: Optional[str] = None
    is_favorite: Optional[bool] = None

class CredentialResponse(CredentialBase):
    id: int
    category_id: int
    created_at: datetime
    updated_at: datetime
    last_used: Optional[datetime] = None
    category: Category

    class Config:
        from_attributes = True

class CredentialWithPassword(CredentialResponse):
    password: str

# Credit Card Schemas
class CreditCardBase(BaseModel):
    card_name: str = Field(..., max_length=255)
    cardholder_name: str = Field(..., max_length=255)
    expiry_month: int = Field(..., ge=1, le=12)
    expiry_year: int = Field(..., ge=2024)
    bank_name: Optional[str] = Field(None, max_length=255)
    card_type: CardTypeEnum = CardTypeEnum.other
    billing_address: Optional[str] = None
    notes: Optional[str] = None

class CreditCardCreate(CreditCardBase):
    card_number: str = Field(..., min_length=13, max_length=19)
    cvv: str = Field(..., min_length=3, max_length=4)

class CreditCardUpdate(BaseModel):
    card_name: Optional[str] = Field(None, max_length=255)
    cardholder_name: Optional[str] = Field(None, max_length=255)
    card_number: Optional[str] = Field(None, min_length=13, max_length=19)
    expiry_month: Optional[int] = Field(None, ge=1, le=12)
    expiry_year: Optional[int] = Field(None, ge=2024)
    cvv: Optional[str] = Field(None, min_length=3, max_length=4)
    bank_name: Optional[str] = Field(None, max_length=255)
    card_type: Optional[CardTypeEnum] = None
    billing_address: Optional[str] = None
    notes: Optional[str] = None

class CreditCardResponse(CreditCardBase):
    id: int
    created_at: datetime
    updated_at: datetime
    card_number_masked: str

    class Config:
        from_attributes = True

class CreditCardWithDetails(CreditCardResponse):
    card_number: str
    cvv: str

# Secure Note Schemas
class SecureNoteBase(BaseModel):
    title: str = Field(..., max_length=255)
    category: Optional[str] = Field(None, max_length=100)
    tags: Optional[str] = Field(None, max_length=500)

class SecureNoteCreate(SecureNoteBase):
    content: str = Field(..., min_length=1)

class SecureNoteUpdate(BaseModel):
    title: Optional[str] = Field(None, max_length=255)
    content: Optional[str] = None
    category: Optional[str] = Field(None, max_length=100)
    tags: Optional[str] = Field(None, max_length=500)

class SecureNoteResponse(SecureNoteBase):
    id: int
    created_at: datetime
    updated_at: datetime

    class Config:
        from_attributes = True

class SecureNoteWithContent(SecureNoteResponse):
    content: str

# Master Password Schema
class MasterPasswordRequest(BaseModel):
    master_password: str = Field(..., min_length=8)

class Token(BaseModel):
    access_token: str
    token_type: str
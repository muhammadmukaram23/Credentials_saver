from cryptography.fernet import Fernet
from cryptography.hazmat.primitives import hashes
from cryptography.hazmat.primitives.kdf.pbkdf2 import PBKDF2HMAC
import base64
import os
from passlib.context import CryptContext
from datetime import datetime, timedelta
from jose import JWTError, jwt

# Password hashing
pwd_context = CryptContext(schemes=["bcrypt"], deprecated="auto")

# JWT settings
SECRET_KEY = os.getenv("SECRET_KEY", "your-secret-key-here-change-in-production")
ALGORITHM = "HS256"
ACCESS_TOKEN_EXPIRE_MINUTES = 30

def verify_password(plain_password, hashed_password):
    return pwd_context.verify(plain_password, hashed_password)

def get_password_hash(password):
    return pwd_context.hash(password)

def create_access_token(data: dict, expires_delta: timedelta = None):
    to_encode = data.copy()
    if expires_delta:
        expire = datetime.utcnow() + expires_delta
    else:
        expire = datetime.utcnow() + timedelta(minutes=15)
    to_encode.update({"exp": expire})
    encoded_jwt = jwt.encode(to_encode, SECRET_KEY, algorithm=ALGORITHM)
    return encoded_jwt

def verify_token(token: str):
    try:
        payload = jwt.decode(token, SECRET_KEY, algorithms=[ALGORITHM])
        return payload
    except JWTError:
        return None

def generate_key_from_password(password: str, salt: bytes = None) -> bytes:
    """Generate encryption key from master password"""
    if salt is None:
        salt = b"salt_1234567890"  # Use a fixed salt for consistency
    
    kdf = PBKDF2HMAC(
        algorithm=hashes.SHA256(),
        length=32,
        salt=salt,
        iterations=100000,
    )
    key = base64.urlsafe_b64encode(kdf.derive(password.encode()))
    return key

def encrypt_data(data: str, master_password: str) -> str:
    """Encrypt data using master password"""
    key = generate_key_from_password(master_password)
    fernet = Fernet(key)
    encrypted_data = fernet.encrypt(data.encode())
    return base64.urlsafe_b64encode(encrypted_data).decode()

def decrypt_data(encrypted_data: str, master_password: str) -> str:
    """Decrypt data using master password"""
    key = generate_key_from_password(master_password)
    fernet = Fernet(key)
    decoded_data = base64.urlsafe_b64decode(encrypted_data.encode())
    decrypted_data = fernet.decrypt(decoded_data)
    return decrypted_data.decode()

def mask_card_number(card_number: str) -> str:
    """Mask credit card number showing only last 4 digits"""
    if len(card_number) < 4:
        return "*" * len(card_number)
    return "*" * (len(card_number) - 4) + card_number[-4:]
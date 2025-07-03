from fastapi import FastAPI, Depends, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from sqlalchemy.orm import Session
import models
from database import engine, get_db
from routes import router
import crud
import schemas

# Create tables
models.Base.metadata.create_all(bind=engine)

app = FastAPI(
    title="Password Manager API",
    description="A secure password manager API with encryption",
    version="1.0.0"
)

# Add CORS middleware
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # Configure this properly in production
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Include routes
app.include_router(router, prefix="/api/v1")

@app.on_event("startup")
async def startup_event():
    """Initialize database with default categories"""
    db = next(get_db())
    
    # Check if categories exist
    existing_categories = crud.get_categories(db)
    if not existing_categories:
        # Create default categories
        default_categories = [
            {"name": "Social Media", "description": "Facebook, Instagram, Twitter, LinkedIn, etc.", "icon": "social"},
            {"name": "Email", "description": "Gmail, Yahoo, Outlook, ProtonMail, etc.", "icon": "email"},
            {"name": "Banking", "description": "Online banking, financial services", "icon": "bank"},
            {"name": "Shopping", "description": "Amazon, eBay, online stores", "icon": "shopping"},
            {"name": "Work", "description": "Work-related accounts and services", "icon": "work"},
            {"name": "Entertainment", "description": "Netflix, Spotify, YouTube, gaming", "icon": "entertainment"},
            {"name": "Cloud Storage", "description": "Google Drive, Dropbox, OneDrive", "icon": "cloud"},
            {"name": "Development", "description": "GitHub, GitLab, coding platforms", "icon": "code"},
            {"name": "VPN/Security", "description": "VPN services, antivirus, security tools", "icon": "security"},
            {"name": "Other", "description": "Miscellaneous accounts", "icon": "other"}
        ]
        
        for category_data in default_categories:
            category = schemas.CategoryCreate(**category_data)
            crud.create_category(db, category)
    
    db.close()

@app.get("/")
async def root():
    return {
        "message": "Password Manager API", 
        "version": "1.0.0",
        "docs": "/docs"
    }

@app.get("/health")
async def health_check():
    return {"status": "healthy"}

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000)
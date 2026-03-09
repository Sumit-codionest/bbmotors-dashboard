# car-dealership-dashboard

## Project Overview
Production-ready full-stack car dealership inventory management system with React + Vite frontend, PHP MVC backend, MySQL database, JWT auth, RBAC, uploads, analytics, and Dockerized deployment.

## Folder Structure
- `frontend/` React admin dashboard.
- `backend/` PHP 8.2 MVC REST API.
- `backend/database/migrations/` SQL schema.
- `backend/database/seeders/` seed data.
- `nginx/` reverse proxy config.
- `docker-compose.yml` orchestration.

## Environment Variables
### Backend (`backend/.env`)
Use `.env.example` values:
- APP_ENV, APP_DEBUG, APP_URL
- JWT_SECRET, JWT_ISSUER, JWT_AUDIENCE, JWT_TTL, REFRESH_TTL
- DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
- CORS_ALLOWED_ORIGINS, RATE_LIMIT, UPLOAD_DIR

### Frontend (`frontend/.env`)
- `VITE_API_URL=http://localhost:8080/api`

## Database Setup
1. Create database/schema from `backend/database/migrations/001_schema.sql`.
2. Seed sample data from `backend/database/seeders/seed.sql`.

## Docker Setup
```bash
docker compose up --build
```
Services:
- App: `http://localhost:8080`
- phpMyAdmin: `http://localhost:8081`

## Local Development Setup
### Backend
```bash
cd backend
cp .env.example .env
composer install
php -S 0.0.0.0:9000 -t public
```

### Frontend
```bash
cd frontend
cp .env.example .env
npm install
npm run dev
```

## API Routes Summary
- Auth: `/api/auth/*`
- Dashboard: `/api/dashboard/*`
- Cars + Images + Bulk Upload: `/api/cars*`
- Brands: `/api/brands`
- Models: `/api/models`
- Features: `/api/features`
- Users: `/api/users`
- Companies: `/api/companies`
- Masters: `/api/countries`, `/api/states`, `/api/cities`, `/api/codes/:codeName`
- Masters Admin CRUD: `/api/masters/:entity` for all required master tables

## Default Admin Credentials
- Username: `admin`
- Password: `admin123`

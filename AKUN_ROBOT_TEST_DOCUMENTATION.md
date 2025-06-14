# Akun Robot Test Cases - Complex Test Suite

## Overview
This document describes the comprehensive test suite for the Akun (Account) functionality in the Laravel 11 project, modeled after the existing ruangan.robot test structure.

## Test File Location
`robot/admin/akun.robot`

## Test Configuration
- **Base URL**: http://localhost:8000
- **Login URL**: http://localhost:8000/login  
- **Akun URL**: http://localhost:8000/akun
- **Browser**: Chrome
- **Admin Credentials**: admin@gmail.com / admin123
- **Timeouts**: 15s (long), 5s (medium), 2s (short)

## Test Cases Overview

### 1. Basic Navigation Tests
- **Buka Akun Page**: Verifies navigation to akun page and table visibility

### 2. Form Validation Tests
- **Tambah Akun tanpa Email**: Tests required email validation
- **Tambah Akun tanpa Password**: Tests required password validation
- **Tambah Akun dengan Email Invalid**: Tests email format validation
- **Tambah Akun dengan Password Kurang dari 3 Karakter**: Tests minimum password length (3 chars)
- **Tambah Akun dengan Password Lebih dari 8 Karakter**: Tests maximum password length (8 chars)
- **Tambah Akun dengan Email yang Sudah Ada**: Tests unique email constraint

### 3. CRUD Operations Tests
- **Tambah Akun - Berhasil**: Tests successful account creation
- **Delete Akun - Berhasil**: Tests successful account deletion with confirmation
- **Delete Akun - Batal**: Tests canceling account deletion

### 4. Data Verification Tests
- **Verifikasi Data Akun di Tabel**: Verifies account data appears in DataTables
- **Verifikasi Modal Tutup Otomatis Setelah Submit Berhasil**: Tests modal auto-close after success
- **Verifikasi Peran Pengguna Otomatis**: Verifies automatic 'pengguna' role assignment

### 5. UI/UX Tests
- **Test Modal Close Button**: Tests modal X button functionality
- **Test Form Reset Setelah Modal Ditutup**: Tests form field reset after modal close/reopen
- **Test Responsif DataTables**: Tests responsive table behavior
- **Test Search Functionality**: Tests DataTables search feature

### 6. Advanced Features Tests
- **Test Bulk Operations (jika ada)**: Tests bulk operations if available

## Key Test Features

### Validation Testing
The test suite comprehensively covers Laravel validation rules:
- Required fields (email, password)
- Email format validation
- Password length constraints (min: 3, max: 8)
- Unique email constraint
- Database relationship validation

### DataTables Integration
Tests DataTables functionality:
- Server-side processing
- Search functionality  
- Responsive design
- Pagination (if present)
- Column sorting and ordering

### Modal Interactions
Comprehensive modal testing:
- Modal open/close behavior
- Form submission handling
- Success/error message display
- Form field reset functionality
- Modal backdrop and escape key handling

### CRUD Operations
Full CRUD testing coverage:
- **Create**: Account creation with validation
- **Read**: Data display in DataTables
- **Update**: Not applicable (akun doesn't have edit functionality)
- **Delete**: Account deletion with confirmation dialogs

### Browser Interactions
Advanced browser interaction testing:
- JavaScript alert handling
- Modal confirmation dialogs
- Dynamic content loading
- AJAX form submissions
- DOM element state verification

## Technical Implementation Details

### Selectors Used
- **Table**: `#akun-table`
- **Modal**: `#authentication-modal`
- **Form Fields**: `#email`, `#password`
- **Buttons**: CSS selectors and XPath expressions
- **Navigation**: `#akun-link`

### Error Handling
- JavaScript alert detection and handling
- Modal confirmation dialog management
- Network timeout handling
- Element visibility verification
- Dynamic content loading waits

### Data Management
- Row counting for verification
- Form field value checking
- Table content validation
- Success/error message detection

## Expected Business Logic Coverage

### Account Management
- User account creation for 'pengguna' role
- Email uniqueness enforcement
- Password security requirements
- Automatic role assignment
- Account deletion with user confirmation

### Security Features
- Input validation and sanitization
- Password hashing (backend)
- Role-based access control
- CSRF protection verification

### User Experience
- Intuitive modal interactions
- Clear validation messages
- Responsive table display
- Search and filter capabilities
- Confirmation dialogs for destructive actions

## Test Execution Recommendations

1. **Pre-test Setup**: Ensure test database is seeded with admin user
2. **Sequential Execution**: Run tests in order for data consistency
3. **Cleanup**: Consider data cleanup between test runs
4. **Environment**: Run on consistent browser and screen resolution
5. **Monitoring**: Watch for network timeouts and AJAX completion

## Integration with Existing Test Suite

This test suite follows the same patterns as `ruangan.robot`:
- Similar variable naming conventions
- Consistent timeout configurations  
- Matching browser interaction patterns
- Similar error handling approaches
- Compatible with existing test infrastructure

## Maintenance Notes

- Update selectors if HTML structure changes
- Modify validation tests if business rules change
- Adjust timeouts based on application performance
- Add new test cases for additional features
- Keep test data consistent with application changes

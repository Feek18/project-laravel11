# Pengguna Robot Test Cases - Complex Test Suite

## Overview
This document describes the comprehensive test suite for the Pengguna (User) functionality in the Laravel 11 project, modeled after the existing ruangan.robot and akun.robot test structures.

## Test File Location
`robot/admin/pengguna.robot`

## Test Configuration
- **Base URL**: http://localhost:8000
- **Login URL**: http://localhost:8000/login  
- **Pengguna URL**: http://localhost:8000/pengguna
- **Browser**: Chrome
- **Admin Credentials**: admin@gmail.com / admin123
- **Timeouts**: 15s (long), 5s (medium), 2s (short)

## Test Cases Overview

### 1. Basic Navigation Tests
- **Buka Pengguna Page**: Verifies navigation to pengguna page and table visibility

### 2. Form Validation Tests
- **Tambah Pengguna tanpa Nama**: Tests required nama validation
- **Tambah Pengguna tanpa Alamat**: Tests required alamat validation  
- **Tambah Pengguna tanpa Gender**: Tests required gender validation
- **Tambah Pengguna dengan Gender Invalid**: Tests gender enum validation (pria/wanita only)
- **Tambah Pengguna dengan No Telepon Terlalu Panjang**: Tests max length validation (20 chars)
- **Tambah Pengguna tanpa No Telepon (Optional Field)**: Tests optional field behavior

### 3. CRUD Operations Tests
- **Tambah Pengguna - Berhasil**: Tests successful user creation with all fields
- **Edit Pengguna - Validasi Nama Kosong**: Tests edit with empty nama validation
- **Edit Pengguna - Validasi Alamat Kosong**: Tests edit with empty alamat validation
- **Edit Pengguna - Berhasil**: Tests successful user editing
- **Delete Pengguna - Berhasil**: Tests successful user deletion with confirmation
- **Delete Pengguna - Batal**: Tests canceling user deletion

### 4. Data Verification Tests
- **Verifikasi Data Pengguna di Tabel**: Verifies user data appears in DataTables

### 5. UI/UX Tests
- **Test Gender Selection Options**: Verifies gender dropdown options (pria, wanita)
- **Test Modal Close Button**: Tests modal X button functionality
- **Test Form Reset Setelah Modal Ditutup**: Tests form field reset after modal close/reopen
- **Test Responsif DataTables**: Tests responsive table behavior
- **Test Search Functionality**: Tests DataTables search feature
- **Test Form Validation Messages**: Tests validation message display

### 6. Advanced Features Tests
- **Test Bulk Operations (jika ada)**: Tests bulk operations if available

## Key Test Features

### Validation Testing
The test suite comprehensively covers Laravel validation rules:
- **Required fields**: nama, alamat, gender
- **Optional fields**: no_telp (nullable)
- **Enum validation**: gender must be 'pria' or 'wanita'
- **Length constraints**: no_telp max 20 characters
- **String validation**: nama and alamat max 255 characters

### DataTables Integration
Tests DataTables functionality:
- Server-side processing
- Search functionality  
- Responsive design
- Pagination (if present)
- Column sorting and ordering
- Indonesian language support

### Modal Interactions
Comprehensive modal testing:
- Modal open/close behavior
- Form submission handling
- Success/error message display
- Form field reset functionality
- Modal backdrop and escape key handling

### CRUD Operations
Full CRUD testing coverage:
- **Create**: User creation with comprehensive validation
- **Read**: Data display in DataTables with proper columns
- **Update**: User editing with validation and success verification
- **Delete**: User deletion with confirmation dialogs

### Gender-Specific Testing
Special focus on gender field:
- Valid options testing (pria, wanita)
- Invalid option rejection
- Required field validation
- Dropdown interaction testing

## Technical Implementation Details

### Selectors Used
- **Table**: `#pengguna-table`
- **Modal**: `#authentication-modal`
- **Form Fields**: `#nama`, `#alamat`, `#gender`, `#no_telp`
- **Buttons**: CSS selectors and XPath expressions
- **Navigation**: `#pengguna-link`

### Form Field Specifications
Based on UserController validation rules:

```php
'nama' => 'required|string|max:255',
'alamat' => 'required|string|max:255', 
'gender' => 'required|in:pria,wanita',
'no_telp' => 'nullable|string|max:20',
```

### Error Handling
- JavaScript alert detection and handling
- Modal confirmation dialog management
- Network timeout handling
- Element visibility verification
- Dynamic content loading waits
- Validation message detection

### Data Management
- Row counting for verification
- Form field value checking
- Table content validation
- Success/error message detection
- Modal state verification

## Expected Business Logic Coverage

### User Management
- User profile creation and editing
- Gender selection with valid options
- Optional phone number handling
- Address and personal information management
- Form validation and error handling

### Security Features
- Input validation and sanitization
- Maximum length constraints
- Enum value validation
- CSRF protection verification
- Required field enforcement

### User Experience
- Intuitive modal interactions
- Clear validation messages
- Responsive table display
- Search and filter capabilities
- Confirmation dialogs for destructive actions
- Form reset functionality

## Test Data Patterns

### Valid Test Data
- **Names**: "Test User Complete", "Pengguna Edited Successfully"
- **Addresses**: "Jl. Test Complete No. 123", "Jl. Edit Success No. 123"
- **Gender**: "pria", "wanita"
- **Phone Numbers**: "081234567890", "081999888777"

### Invalid Test Data
- **Empty Required Fields**: "", null values
- **Invalid Gender**: "invalid", "other"
- **Long Phone Numbers**: "081234567890123456789012345" (>20 chars)
- **Invalid Enum Values**: Modified dropdown options

## Test Execution Recommendations

1. **Pre-test Setup**: Ensure test database is seeded with admin user
2. **Sequential Execution**: Run tests in order for data consistency
3. **Cleanup**: Consider data cleanup between test runs
4. **Environment**: Run on consistent browser and screen resolution
5. **Monitoring**: Watch for network timeouts and AJAX completion

## Integration with Existing Test Suite

This test suite follows the same patterns as `ruangan.robot` and `akun.robot`:
- Similar variable naming conventions
- Consistent timeout configurations  
- Matching browser interaction patterns
- Similar error handling approaches
- Compatible with existing test infrastructure
- Same documentation style and structure

## Controller-Specific Testing

### UserController Method Coverage
- **index()**: Table display and DataTables functionality
- **store()**: User creation with validation
- **edit()**: Edit modal loading and display
- **update()**: User modification with validation  
- **destroy()**: User deletion with confirmation

### Model Integration
- **Pengguna Model**: Tests CRUD operations
- **Database Relations**: Tests if user_id relationships work
- **Mass Assignment**: Tests fillable fields functionality

## Maintenance Notes

- Update selectors if HTML structure changes
- Modify validation tests if business rules change
- Adjust timeouts based on application performance
- Add new test cases for additional features
- Keep test data consistent with application changes
- Monitor for changes in gender enum values
- Update phone number validation if max length changes

## Special Considerations

### Gender Field Testing
The gender field requires special attention as it's an enum field with only two valid values. Tests specifically verify:
- Valid option selection
- Invalid option rejection
- Required field behavior
- Dropdown interaction

### Optional Fields
No telepon is nullable, so tests verify:
- Successful submission without phone number
- Validation when phone number is provided
- Length constraints when phone number is present

### Indonesian Language Support
DataTables uses Indonesian language pack, so error messages and UI elements may appear in Indonesian. Tests account for both English and Indonesian messages where applicable.

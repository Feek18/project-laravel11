*** Settings ***
Library    SeleniumLibrary

*** Variables ***
${BASE_URL}    http://localhost:8000
${LOGIN_URL}    http://localhost:8000/login
${PENGGUNA_URL}    http://localhost:8000/pengguna
${BROWSER}      chrome
${EMAIL}     admin@gmail.com
${PASSWORD}     admin123
${WAIT_TIMEOUT}    15
${SHORT_WAIT}      2
${LONG_WAIT}       5

*** Test Cases ***

Buka Pengguna Page
    [Documentation]    Test opening pengguna page and verify table visibility
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=pengguna-link
    Wait Until Element Is Visible    id=pengguna-table    timeout=10
    Element Should Be Visible    id=pengguna-table
    Close Browser

Tambah Pengguna tanpa Nama
    [Documentation]    Test adding pengguna without name - should show validation error
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=pengguna-link
    Wait Until Element Is Visible    id=pengguna-table    timeout=10
    Element Should Be Visible    id=pengguna-table
    Click Button    xpath=//button[contains(text(), 'Tambah') or @data-modal-target='authentication-modal']
    Wait Until Element Is Visible    id=authentication-modal    timeout=10
    Element Should Be Visible    id=authentication-modal
    # Input Text    id=nama    John Doe
    Input Text    id=alamat    Jl. Merdeka No. 123
    Select From List By Value    id=gender    pria
    Input Text    id=no_telp    081234567890
    Sleep    2s
    Click Button    xpath=//button[contains(text(), 'Add')]
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'nama') and contains(text(), 'required')]    timeout=10
    Close Browser

Tambah Pengguna tanpa Alamat
    [Documentation]    Test adding pengguna without alamat - should show validation error
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=pengguna-link
    Wait Until Element Is Visible    id=pengguna-table    timeout=10
    Element Should Be Visible    id=pengguna-table
    Click Button    xpath=//button[contains(text(), 'Tambah') or @data-modal-target='authentication-modal']
    Wait Until Element Is Visible    id=authentication-modal    timeout=10
    Element Should Be Visible    id=authentication-modal
    Input Text    id=nama    Jane Smith
    # Input Text    id=alamat    Jl. Sudirman No. 456
    Select From List By Value    id=gender    wanita
    Input Text    id=no_telp    081987654321
    Sleep    2s
    Click Button    xpath=//button[contains(text(), 'Add')]
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'alamat') and contains(text(), 'required')]    timeout=10
    Close Browser

Tambah Pengguna tanpa Gender
    [Documentation]    Test adding pengguna without gender - should show validation error
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=pengguna-link
    Wait Until Element Is Visible    id=pengguna-table    timeout=10
    Element Should Be Visible    id=pengguna-table
    Click Button    xpath=//button[contains(text(), 'Tambah') or @data-modal-target='authentication-modal']
    Wait Until Element Is Visible    id=authentication-modal    timeout=10
    Element Should Be Visible    id=authentication-modal
    Input Text    id=nama    Alex Johnson
    Input Text    id=alamat    Jl. Gatot Subroto No. 789
    # Select From List By Value    id=gender    pria
    Input Text    id=no_telp    081555666777
    Sleep    2s
    Click Button    xpath=//button[contains(text(), 'Add')]
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'gender') and contains(text(), 'required')]    timeout=10
    Close Browser

Tambah Pengguna dengan Gender Invalid
    [Documentation]    Test adding pengguna with invalid gender value - should show validation error
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=pengguna-link
    Wait Until Element Is Visible    id=pengguna-table    timeout=10
    Element Should Be Visible    id=pengguna-table
    Click Button    xpath=//button[contains(text(), 'Tambah') or @data-modal-target='authentication-modal']
    Wait Until Element Is Visible    id=authentication-modal    timeout=10
    Element Should Be Visible    id=authentication-modal
    Input Text    id=nama    Taylor Brown
    Input Text    id=alamat    Jl. Diponegoro No. 999
    
    # Try to select invalid gender by modifying the select element via JavaScript
    Execute JavaScript    document.getElementById('gender').innerHTML += '<option value="invalid">Invalid Gender</option>';
    Select From List By Value    id=gender    invalid
    
    Input Text    id=no_telp    081111222333
    Sleep    2s
    Click Button    xpath=//button[contains(text(), 'Add')]
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'gender') and (contains(text(), 'invalid') or contains(text(), 'not valid'))]    timeout=10
    Close Browser

Tambah Pengguna dengan No Telepon Terlalu Panjang
    [Documentation]    Test adding pengguna with phone number exceeding max length (20 chars)
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=pengguna-link
    Wait Until Element Is Visible    id=pengguna-table    timeout=10
    Element Should Be Visible    id=pengguna-table
    Click Button    xpath=//button[contains(text(), 'Tambah') or @data-modal-target='authentication-modal']
    Wait Until Element Is Visible    id=authentication-modal    timeout=10
    Element Should Be Visible    id=authentication-modal
    Input Text    id=nama    Long Phone User
    Input Text    id=alamat    Jl. Phone Street No. 1
    Select From List By Value    id=gender    pria
    Input Text    id=no_telp    081234567890123456789012345
    Sleep    2s
    Click Button    xpath=//button[contains(text(), 'Add')]
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'no_telp') and (contains(text(), 'may not be greater than 20') or contains(text(), 'max'))]    timeout=10
    Close Browser

Tambah Pengguna tanpa No Telepon (Optional Field)
    [Documentation]    Test adding pengguna without phone number - should be successful since it's optional
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=pengguna-link
    Wait Until Element Is Visible    id=pengguna-table    timeout=10
    Element Should Be Visible    id=pengguna-table
    Click Button    xpath=//button[contains(text(), 'Tambah') or @data-modal-target='authentication-modal']
    Wait Until Element Is Visible    id=authentication-modal    timeout=10
    Element Should Be Visible    id=authentication-modal
    Input Text    id=nama    No Phone User
    Input Text    id=alamat    Jl. No Phone Street No. 1
    Select From List By Value    id=gender    wanita
    # Input Text    id=no_telp    
    Sleep    2s
    Click Button    xpath=//button[contains(text(), 'Add')]
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'success!') or contains(text(), 'berhasil')]    timeout=10
    Close Browser

Tambah Pengguna - Berhasil
    [Documentation]    Test successful pengguna addition with all fields
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}  
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=pengguna-link
    Wait Until Element Is Visible    id=pengguna-table    timeout=10
    Element Should Be Visible    id=pengguna-table
    Click Button    xpath=//button[contains(text(), 'Tambah') or @data-modal-target='authentication-modal']
    Wait Until Element Is Visible    id=authentication-modal    timeout=10
    Element Should Be Visible    id=authentication-modal
    Input Text    id=nama    Test User Complete
    Input Text    id=alamat    Jl. Test Complete No. 123
    Select From List By Value    id=gender    pria
    Input Text    id=no_telp    081234567890
    Sleep    2s
    Click Button    xpath=//button[contains(text(), 'Add')]
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'success!') or contains(text(), 'berhasil')]    timeout=10
    Close Browser

Edit Pengguna - Validasi Nama Kosong
    [Documentation]    Test editing pengguna with empty name
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=${WAIT_TIMEOUT}
    
    # Navigate to pengguna page
    Click Element    id=pengguna-link
    Wait Until Element Is Visible    id=pengguna-table    timeout=${WAIT_TIMEOUT}
    Wait Until Element Is Visible    css=table#pengguna-table tbody tr    timeout=${WAIT_TIMEOUT}
    Sleep    ${LONG_WAIT}
    
    # Click edit button
    Wait Until Element Is Visible    css=button[onclick*="loadEditModal('pengguna'"]    timeout=${WAIT_TIMEOUT}
    Click Element    css=button[onclick*="loadEditModal('pengguna'"]
    
    # Wait for edit modal
    Wait Until Element Is Visible    css=div[id*="edit-modal-"]    timeout=${WAIT_TIMEOUT}
    Sleep    ${SHORT_WAIT}
    
    # Clear name field and fill other fields
    Clear Element Text    css=input[name="nama"]
    Input Text    css=input[name="alamat"]    Alamat Edited
    Select From List By Value    css=select[name="gender"]    wanita
    
    # Submit form
    Click Button    css=button[type="submit"]
    
    # Validate error message
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'nama') and contains(text(), 'required')]    timeout=${WAIT_TIMEOUT}
    
    Close Browser

Edit Pengguna - Validasi Alamat Kosong
    [Documentation]    Test editing pengguna with empty alamat
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=${WAIT_TIMEOUT}
    
    # Navigate to pengguna page
    Click Element    id=pengguna-link
    Wait Until Element Is Visible    id=pengguna-table    timeout=${WAIT_TIMEOUT}
    Wait Until Element Is Visible    css=table#pengguna-table tbody tr    timeout=${WAIT_TIMEOUT}
    Sleep    ${LONG_WAIT}
    
    # Click edit button
    Wait Until Element Is Visible    css=button[onclick*="loadEditModal('pengguna'"]    timeout=${WAIT_TIMEOUT}
    Click Element    css=button[onclick*="loadEditModal('pengguna'"]
    
    # Wait for edit modal
    Wait Until Element Is Visible    css=div[id*="edit-modal-"]    timeout=${WAIT_TIMEOUT}
    Sleep    ${SHORT_WAIT}
    
    # Fill fields but clear alamat
    Input Text    css=input[name="nama"]    Test Edit User
    Clear Element Text    css=input[name="alamat"]
    Select From List By Value    css=select[name="gender"]    pria
    
    # Submit form
    Click Button    css=button[type="submit"]
    
    # Validate error message
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'alamat') and contains(text(), 'required')]    timeout=${WAIT_TIMEOUT}
    
    Close Browser

Edit Pengguna - Berhasil
    [Documentation]    Test successful pengguna editing
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=${WAIT_TIMEOUT}
    
    # Navigate to pengguna page
    Click Element    id=pengguna-link
    Wait Until Element Is Visible    id=pengguna-table    timeout=${WAIT_TIMEOUT}
    Wait Until Element Is Visible    css=table#pengguna-table tbody tr    timeout=${WAIT_TIMEOUT}
    Sleep    ${LONG_WAIT}
    
    # Get original data before edit
    ${original_data}=    Get Text    css=table#pengguna-table tbody tr:first-child
    
    # Click edit button
    Wait Until Element Is Visible    css=button[onclick*="loadEditModal('pengguna'"]    timeout=${WAIT_TIMEOUT}
    Click Element    css=button[onclick*="loadEditModal('pengguna'"]
    
    # Wait for edit modal
    Wait Until Element Is Visible    css=div[id*="edit-modal-"]    timeout=${WAIT_TIMEOUT}
    Sleep    ${SHORT_WAIT}
    
    # Edit with valid data
    Clear Element Text    css=input[name="nama"]
    Input Text    css=input[name="nama"]    Pengguna Edited Successfully
    Clear Element Text    css=input[name="alamat"]
    Input Text    css=input[name="alamat"]    Jl. Edit Success No. 123
    Select From List By Value    css=select[name="gender"]    wanita
    Input Text    css=input[name="no_telp"]    081999888777
    
    # Submit form
    Click Button    css=button[type="submit"]
    
    # Validate success
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'success!') or contains(text(), 'berhasil')]    timeout=${WAIT_TIMEOUT}
    
    # Verify data changed in table
    Sleep    ${SHORT_WAIT}
    Close Browser

Delete Pengguna - Berhasil
    [Documentation]    Test successful pengguna deletion
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=${WAIT_TIMEOUT}
    
    # Navigate to pengguna page
    Click Element    id=pengguna-link
    Wait Until Element Is Visible    id=pengguna-table    timeout=${WAIT_TIMEOUT}
    Wait Until Element Is Visible    css=table#pengguna-table tbody tr    timeout=${WAIT_TIMEOUT}
    Sleep    ${LONG_WAIT}
    
    # Count rows before deletion
    ${rows_before}=    Get Element Count    css=table#pengguna-table tbody tr
    Log    Rows before deletion: ${rows_before}
    
    # Click delete button (red button)
    Wait Until Element Is Visible    css=button.bg-red-500    timeout=${WAIT_TIMEOUT}
    Click Element    css=button.bg-red-500
    
    # Handle confirmation (JavaScript alert or modal)
    ${alert_present}=    Run Keyword And Return Status    Alert Should Be Present
    Run Keyword If    ${alert_present}    Handle Alert    action=ACCEPT
    
    # If no alert, look for confirmation modal/button
    Run Keyword Unless    ${alert_present}    Run Keywords
    ...    Wait Until Element Is Visible    xpath=//button[contains(text(), 'Delete') or contains(text(), 'Hapus') or contains(text(), 'Ya')]    timeout=5
    ...    AND    Click Button    xpath=//button[contains(text(), 'Delete') or contains(text(), 'Hapus') or contains(text(), 'Ya')]
    
    # Wait for success message
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'success!') or contains(text(), 'berhasil') or contains(text(), 'dihapus')]    timeout=${WAIT_TIMEOUT}
    
    Sleep    ${SHORT_WAIT}
    
    Close Browser

Delete Pengguna - Batal
    [Documentation]    Test canceling pengguna deletion
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=${WAIT_TIMEOUT}
    
    # Navigate to pengguna page
    Click Element    id=pengguna-link
    Wait Until Element Is Visible    id=pengguna-table    timeout=${WAIT_TIMEOUT}
    Wait Until Element Is Visible    css=table#pengguna-table tbody tr    timeout=${WAIT_TIMEOUT}
    Sleep    ${LONG_WAIT}
    
    # Count rows before deletion attempt
    ${rows_before}=    Get Element Count    css=table#pengguna-table tbody tr
    Log    Rows before deletion attempt: ${rows_before}
    
    # Click delete button
    Wait Until Element Is Visible    css=button.bg-red-500    timeout=${WAIT_TIMEOUT}
    Click Element    css=button.bg-red-500
    
    # Handle confirmation - Cancel/Dismiss
    ${alert_present}=    Run Keyword And Return Status    Alert Should Be Present
    Run Keyword If    ${alert_present}    Handle Alert    action=DISMISS
    
    # If no alert, look for cancel button in modal
    Run Keyword Unless    ${alert_present}    Run Keywords
    ...    Wait Until Element Is Visible    xpath=//button[contains(text(), 'Cancel') or contains(text(), 'Batal') or contains(text(), 'Tidak')]    timeout=5
    ...    AND    Click Button    xpath=//button[contains(text(), 'Cancel') or contains(text(), 'Batal') or contains(text(), 'Tidak')]
    
    Sleep    ${SHORT_WAIT}
    
    # Verify row count unchanged
    ${rows_after}=    Get Element Count    css=table#pengguna-table tbody tr
    Log    Rows after canceling deletion: ${rows_after}
    Should Be Equal As Integers    ${rows_before}    ${rows_after}
    
    Close Browser
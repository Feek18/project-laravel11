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
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'The selected gender is invalid.') and contains(text(), 'required')]    timeout=10
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
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'The no telp field must not be greater than 20 characters.') and (contains(text(), 'may not be greater than 20') or contains(text(), 'max'))]    timeout=10
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
    
    # Wait for DataTable to load data
    Wait Until Element Is Visible    css=table#pengguna-table tbody tr    timeout=${WAIT_TIMEOUT}
    Sleep    ${LONG_WAIT}
    
    # Click edit button (yellow button) - first one in table
    Wait Until Element Is Visible    css=button.bg-yellow-500    timeout=${WAIT_TIMEOUT}
    Click Element    css=button.bg-yellow-500
    
    # Wait for edit modal to appear and become visible
    Wait Until Element Is Visible    css=div[id*="edit-modal-"]    timeout=${WAIT_TIMEOUT}
    Sleep    ${SHORT_WAIT}
    
    # Use more specific selectors that target the edit modal specifically
    # Clear name field (make it empty for validation test) and fill other fields
    Wait Until Element Is Enabled    css=div[id*="edit-modal-"] input[name="nama"]    timeout=${WAIT_TIMEOUT}
    Scroll Element Into View    css=div[id*="edit-modal-"] input[name="nama"]
    Click Element    css=div[id*="edit-modal-"] input[name="nama"]
    Sleep    1s
    Press Keys    css=div[id*="edit-modal-"] input[name="nama"]    CTRL+a
    Press Keys    css=div[id*="edit-modal-"] input[name="nama"]    DELETE
    
    Wait Until Element Is Enabled    css=div[id*="edit-modal-"] input[name="alamat"]    timeout=${WAIT_TIMEOUT}
    Scroll Element Into View    css=div[id*="edit-modal-"] input[name="alamat"]
    Click Element    css=div[id*="edit-modal-"] input[name="alamat"]
    Sleep    1s
    Press Keys    css=div[id*="edit-modal-"] input[name="alamat"]    CTRL+a
    Press Keys    css=div[id*="edit-modal-"] input[name="alamat"]    DELETE
    Input Text    css=div[id*="edit-modal-"] input[name="alamat"]    Alamat Edited
    
    Select From List By Value    css=div[id*="edit-modal-"] select[name="gender"]    wanita
    
    # Submit form
    Click Button    css=div[id*="edit-modal-"] button[type="submit"]
    
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
    
    # Wait for DataTable to load data
    Wait Until Element Is Visible    css=table#pengguna-table tbody tr    timeout=${WAIT_TIMEOUT}
    Sleep    ${LONG_WAIT}
    
    # Click edit button (yellow button) - first one in table
    Wait Until Element Is Visible    css=button.bg-yellow-500    timeout=${WAIT_TIMEOUT}
    Click Element    css=button.bg-yellow-500
      # Wait for edit modal to appear and become visible
    Wait Until Element Is Visible    css=div[id*="edit-modal-"]    timeout=${WAIT_TIMEOUT}
    Sleep    ${SHORT_WAIT}
    
    # Use more specific selectors that target the edit modal specifically
    # Fill name field and clear alamat field (for validation test)
    Wait Until Element Is Enabled    css=div[id*="edit-modal-"] input[name="nama"]    timeout=${WAIT_TIMEOUT}
    Scroll Element Into View    css=div[id*="edit-modal-"] input[name="nama"]
    Click Element    css=div[id*="edit-modal-"] input[name="nama"]
    Sleep    1s
    Press Keys    css=div[id*="edit-modal-"] input[name="nama"]    CTRL+a
    Press Keys    css=div[id*="edit-modal-"] input[name="nama"]    DELETE
    Input Text    css=div[id*="edit-modal-"] input[name="nama"]    Test Edit User
    
    Wait Until Element Is Enabled    css=div[id*="edit-modal-"] input[name="alamat"]    timeout=${WAIT_TIMEOUT}
    Scroll Element Into View    css=div[id*="edit-modal-"] input[name="alamat"]
    Click Element    css=div[id*="edit-modal-"] input[name="alamat"]
    Sleep    1s
    Press Keys    css=div[id*="edit-modal-"] input[name="alamat"]    CTRL+a
    Press Keys    css=div[id*="edit-modal-"] input[name="alamat"]    DELETE
    
    Select From List By Value    css=div[id*="edit-modal-"] select[name="gender"]    pria
    
    # Submit form
    Click Button    css=div[id*="edit-modal-"] button[type="submit"]
    
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
    
    # Wait for DataTable to load data
    Wait Until Element Is Visible    css=table#pengguna-table tbody tr    timeout=${WAIT_TIMEOUT}
    Sleep    ${LONG_WAIT}
    
    # Get original data before edit
    ${original_data}=    Get Text    css=table#pengguna-table tbody tr:first-child
    
    # Click edit button (yellow button) - first one in table
    Wait Until Element Is Visible    css=button.bg-yellow-500    timeout=${WAIT_TIMEOUT}
    Click Element    css=button.bg-yellow-500
      # Wait for edit modal to appear and become visible
    Wait Until Element Is Visible    css=div[id*="edit-modal-"]    timeout=${WAIT_TIMEOUT}
    Sleep    ${SHORT_WAIT}
    
    # Use more specific selectors that target the edit modal specifically
    # Edit with valid data using robust interaction
    Wait Until Element Is Enabled    css=div[id*="edit-modal-"] input[name="nama"]    timeout=${WAIT_TIMEOUT}
    Scroll Element Into View    css=div[id*="edit-modal-"] input[name="nama"]
    Click Element    css=div[id*="edit-modal-"] input[name="nama"]
    Sleep    1s
    Press Keys    css=div[id*="edit-modal-"] input[name="nama"]    CTRL+a
    Press Keys    css=div[id*="edit-modal-"] input[name="nama"]    DELETE
    Input Text    css=div[id*="edit-modal-"] input[name="nama"]    Pengguna Edited Successfully
    
    Wait Until Element Is Enabled    css=div[id*="edit-modal-"] input[name="alamat"]    timeout=${WAIT_TIMEOUT}
    Scroll Element Into View    css=div[id*="edit-modal-"] input[name="alamat"]
    Click Element    css=div[id*="edit-modal-"] input[name="alamat"]
    Sleep    1s
    Press Keys    css=div[id*="edit-modal-"] input[name="alamat"]    CTRL+a
    Press Keys    css=div[id*="edit-modal-"] input[name="alamat"]    DELETE
    Input Text    css=div[id*="edit-modal-"] input[name="alamat"]    Jl. Edit Success No. 123
    
    Select From List By Value    css=div[id*="edit-modal-"] select[name="gender"]    wanita
    
    Wait Until Element Is Enabled    css=div[id*="edit-modal-"] input[name="no_telp"]    timeout=${WAIT_TIMEOUT}
    Scroll Element Into View    css=div[id*="edit-modal-"] input[name="no_telp"]
    Click Element    css=div[id*="edit-modal-"] input[name="no_telp"]
    Sleep    1s
    Press Keys    css=div[id*="edit-modal-"] input[name="no_telp"]    CTRL+a
    Press Keys    css=div[id*="edit-modal-"] input[name="no_telp"]    DELETE
    Input Text    css=div[id*="edit-modal-"] input[name="no_telp"]    081999888777
    
    # Submit form
    Click Button    css=div[id*="edit-modal-"] button[type="submit"]
    
    # Validate success
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'success!') or contains(text(), 'berhasil')]    timeout=${WAIT_TIMEOUT}
    
    # Verify data changed in table
    Sleep    ${SHORT_WAIT}
    Close Browser
    Clear Element Text    css=input[name="no_telp"]
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

Debug Edit Button Visibility
    [Documentation]    Debug test to check if edit buttons are visible and clickable
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=${WAIT_TIMEOUT}
    
    # Navigate to pengguna page
    Click Element    id=pengguna-link
    Wait Until Element Is Visible    id=pengguna-table    timeout=${WAIT_TIMEOUT}
    
    # Wait longer for DataTable to load and populate
    Sleep    10s
    
    # Check if there are any rows in the table
    ${row_count}=    Get Element Count    css=table#pengguna-table tbody tr
    Log    Number of rows found: ${row_count}
    
    # If no rows, this might be the issue
    Run Keyword If    ${row_count} == 0    Log    WARNING: No data rows found in pengguna table!
    
    # Check if edit buttons exist
    ${button_count}=    Get Element Count    css=button.bg-yellow-500
    Log    Number of edit buttons found: ${button_count}
    
    # If buttons exist, try to click first one
    Run Keyword If    ${button_count} > 0    Run Keywords
    ...    Log    Edit buttons found, attempting to click first one
    ...    AND    Click Element    css=button.bg-yellow-500
    ...    AND    Sleep    5s
    ...    AND    Log    Clicked edit button successfully
    
    # Check if modal appeared
    ${modal_visible}=    Run Keyword And Return Status    Element Should Be Visible    css=div[id*="edit-modal-"]
    Log    Edit modal visible: ${modal_visible}
    
    Close Browser

Test Full Edit Flow - Create Then Edit
    [Documentation]    Test creating data first, then editing it
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=${WAIT_TIMEOUT}
    
    # Navigate to pengguna page
    Click Element    id=pengguna-link
    Wait Until Element Is Visible    id=pengguna-table    timeout=${WAIT_TIMEOUT}
    
    # First, add a test user to ensure we have data to edit
    Click Button    xpath=//button[contains(text(), 'Tambah') or @data-modal-target='authentication-modal']
    Wait Until Element Is Visible    id=authentication-modal    timeout=10
    Input Text    id=nama    Test User For Edit
    Input Text    id=alamat    Jl. Test Original No. 123
    Select From List By Value    id=gender    pria
    Input Text    id=no_telp    081234567890
    Sleep    2s
    Click Button    xpath=//button[contains(text(), 'Add')]
    
    # Wait for success message and modal to close
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'success!') or contains(text(), 'berhasil')]    timeout=10
    Sleep    3s
    
    # Now try to edit the newly created user
    # Refresh the page to ensure DataTable reloads
    Reload Page
    Wait Until Element Is Visible    id=pengguna-table    timeout=${WAIT_TIMEOUT}
    Sleep    5s
    
    # Look for edit buttons
    ${button_count}=    Get Element Count    css=button.bg-yellow-500
    Log    Number of edit buttons after creating user: ${button_count}
    
    # If we have edit buttons, try to edit
    Run Keyword If    ${button_count} > 0    Run Keywords
    ...    Log    Found edit buttons, proceeding with edit test
    ...    AND    Wait Until Element Is Visible    css=button.bg-yellow-500    timeout=${WAIT_TIMEOUT}
    ...    AND    Click Element    css=button.bg-yellow-500
    ...    AND    Wait Until Element Is Visible    css=div[id*="edit-modal-"]    timeout=${WAIT_TIMEOUT}
    ...    AND    Wait Until Element Is Enabled    id=nama    timeout=${WAIT_TIMEOUT}
    ...    AND    Scroll Element Into View    id=nama
    ...    AND    Click Element    id=nama
    ...    AND    Sleep    1s
    ...    AND    Press Keys    id=nama    CTRL+a
    ...    AND    Input Text    id=nama    Test User EDITED
    ...    AND    Click Button    css=button[type="submit"]
    ...    AND    Wait Until Element Is Visible    xpath=//*[contains(text(), 'success!') or contains(text(), 'berhasil')]    timeout=${WAIT_TIMEOUT}
    ...    AND    Log    Edit operation completed successfully
    
    Close Browser

Debug DataTable State
    [Documentation]    Debug DataTable loading and button generation
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=${WAIT_TIMEOUT}
    
    # Navigate to pengguna page
    Click Element    id=pengguna-link
    Wait Until Element Is Visible    id=pengguna-table    timeout=${WAIT_TIMEOUT}
    
    # Wait for DataTable to initialize
    Sleep    10s
    
    # Execute JavaScript to check DataTable state
    ${datatableInfo}=    Execute JavaScript    return $('#pengguna-table').DataTable().data().length;
    Log    DataTable row count: ${datatableInfo}
    
    # Check table HTML content
    ${tableHTML}=    Execute JavaScript    return document.querySelector('#pengguna-table tbody').innerHTML;
    Log    Table body HTML: ${tableHTML}
    
    # Force DataTable reload
    Execute JavaScript    $('#pengguna-table').DataTable().ajax.reload();
    Sleep    5s
    
    # Check again after reload
    ${datatableInfo2}=    Execute JavaScript    return $('#pengguna-table').DataTable().data().length;
    Log    DataTable row count after reload: ${datatableInfo2}
    
    # Check for buttons using JavaScript
    ${buttonCount}=    Execute JavaScript    return document.querySelectorAll('button.bg-yellow-500').length;
    Log    Edit buttons found via JavaScript: ${buttonCount}
    
    # If buttons exist, try JavaScript click
    Run Keyword If    ${buttonCount} > 0    Run Keywords
    ...    Execute JavaScript    document.querySelector('button.bg-yellow-500').click();
    ...    AND    Sleep    3s
    ...    AND    Log    Clicked edit button via JavaScript
    
    Close Browser

Edit Pengguna - JavaScript Approach
    [Documentation]    Alternative edit test using JavaScript for form interaction
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=${WAIT_TIMEOUT}
    
    # Navigate to pengguna page
    Click Element    id=pengguna-link
    Wait Until Element Is Visible    id=pengguna-table    timeout=${WAIT_TIMEOUT}
    
    # Wait for DataTable to load data
    Wait Until Element Is Visible    css=table#pengguna-table tbody tr    timeout=${WAIT_TIMEOUT}
    Sleep    ${LONG_WAIT}
    
    # Click edit button using JavaScript if needed
    ${button_count}=    Get Element Count    css=button.bg-yellow-500
    Run Keyword If    ${button_count} > 0    Execute JavaScript    document.querySelector('button.bg-yellow-500').click();
    
    # Wait for edit modal to appear
    Wait Until Element Is Visible    css=div[id*="edit-modal-"]    timeout=${WAIT_TIMEOUT}
    Sleep    3s
    
    # Use JavaScript to interact with form fields to avoid interaction issues
    Execute JavaScript    
    ...    var modal = document.querySelector('div[id*="edit-modal-"]');
    ...    if (modal) {
    ...        var namaField = modal.querySelector('input[name="nama"]');
    ...        var alamatField = modal.querySelector('input[name="alamat"]');
    ...        var genderField = modal.querySelector('select[name="gender"]');
    ...        
    ...        if (namaField) {
    ...            namaField.focus();
    ...            namaField.select();
    ...            namaField.value = 'JS Edited User';
    ...            namaField.dispatchEvent(new Event('input', { bubbles: true }));
    ...        }
    ...        
    ...        if (alamatField) {
    ...            alamatField.focus();
    ...            alamatField.select();
    ...            alamatField.value = 'JS Edited Address';
    ...            alamatField.dispatchEvent(new Event('input', { bubbles: true }));
    ...        }
    ...        
    ...        if (genderField) {
    ...            genderField.value = 'wanita';
    ...            genderField.dispatchEvent(new Event('change', { bubbles: true }));
    ...        }
    ...    }
    
    Sleep    2s
    
    # Submit form using JavaScript
    Execute JavaScript    
    ...    var modal = document.querySelector('div[id*="edit-modal-"]');
    ...    if (modal) {
    ...        var submitBtn = modal.querySelector('button[type="submit"]');
    ...        if (submitBtn) submitBtn.click();
    ...    }
    
    # Wait for success message
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'success!') or contains(text(), 'berhasil')]    timeout=${WAIT_TIMEOUT}
    
    Close Browser
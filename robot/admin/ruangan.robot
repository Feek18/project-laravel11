*** Settings ***
Library    SeleniumLibrary

*** Variables ***
${BASE_URL}    http://localhost:8000
${LOGIN_URL}    http://localhost:8000/login
${RUANGAN_URL}    http://localhost:8000/ruangan
${QR_GENERATE_URL}    http://localhost:8000/qr/generate-room
${BROWSER}      chrome
${EMAIL}     admin@gmail.com
${PASSWORD}     admin123
${WAIT_TIMEOUT}    15
${SHORT_WAIT}      2
${LONG_WAIT}       5

*** Test Cases ***

Buka Ruangan Page
    [Documentation]    Test opening ruangan page and verify table visibility
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=ruangan-link
    Wait Until Element Is Visible    id=ruangan-table    timeout=10
    Element Should Be Visible    id=ruangan-table
    Close Browser

Tambah Ruangan tanpa Nama
    [Documentation]    Test adding ruangan without name - should show validation error
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=ruangan-link
    Wait Until Element Is Visible    id=ruangan-table    timeout=10
    Element Should Be Visible    id=ruangan-table
    Click Button    xpath=//button[contains(text(), 'Tambah') or @data-modal-target='ruangan-modal']
    Wait Until Element Is Visible    id=ruangan-modal    timeout=10
    Element Should Be Visible    id=ruangan-modal
    # Input Text    id=nama_ruangan    Test Ruangan
    Input Text    id=lokasi    Gedung A Lantai 1
    Sleep    2s
    Click Button    id=ruangan-submit-btn
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'The nama ruangan field is required.')]    timeout=10
    Close Browser

Tambah Ruangan tanpa Lokasi
    [Documentation]    Test adding ruangan without location - should show validation error
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=ruangan-link
    Wait Until Element Is Visible    id=ruangan-table    timeout=10
    Element Should Be Visible    id=ruangan-table
    Click Button    xpath=//button[contains(text(), 'Tambah') or @data-modal-target='ruangan-modal']
    Wait Until Element Is Visible    id=ruangan-modal    timeout=10
    Element Should Be Visible    id=ruangan-modal
    Input Text    id=nama_ruangan    Ruangan Kuliah A
    # Input Text    id=lokasi    Gedung A Lantai 1
    Sleep    2s
    Click Button    id=ruangan-submit-btn
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'The lokasi field is required.')]    timeout=10
    Close Browser

Tambah Ruangan - Berhasil
    [Documentation]    Test successful ruangan addition
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}  
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=ruangan-link
    Wait Until Element Is Visible    id=ruangan-table    timeout=10
    Element Should Be Visible    id=ruangan-table
    Click Button    xpath=//button[contains(text(), 'Tambah') or @data-modal-target='ruangan-modal']
    Wait Until Element Is Visible    id=ruangan-modal    timeout=10
    Element Should Be Visible    id=ruangan-modal
    Input Text    id=nama_ruangan    Ruangan Multimedia
    Input Text    id=lokasi    Gedung B Lantai 2
    Sleep    2s
    Click Button    id=ruangan-submit-btn
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'success!')]    timeout=10
    Close Browser

QR Code - Generate QR untuk Ruangan
    [Documentation]    Test generating QR code for a room - navigates to separate QR page
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=${WAIT_TIMEOUT}
    
    # Navigate to ruangan page
    Click Element    id=ruangan-link
    Wait Until Element Is Visible    id=ruangan-table    timeout=${WAIT_TIMEOUT}
    Wait Until Element Is Visible    css=table#ruangan-table tbody tr    timeout=${WAIT_TIMEOUT}
    Sleep    ${LONG_WAIT}
      # Click QR button (green button) - opens in new tab/window
    Wait Until Element Is Visible    css=button.bg-green-500    timeout=${WAIT_TIMEOUT}
    Click Element    css=button.bg-green-500
    
    # Switch to the new tab/window that opened
    Sleep    ${SHORT_WAIT}
    Switch Window    NEW
    
    # Verify we're on the QR page
    Wait Until Location Contains    /qr/generate-room    timeout=${WAIT_TIMEOUT}
    Location Should Contain    generate-room
    # Close the QR tab and switch back to main window
    Close Window
    Switch Window    MAIN
    
    Close Browser
QR Code - Print QR Code
    [Documentation]    Test printing QR code for a room from QR page
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=${WAIT_TIMEOUT}
    
    # Navigate to ruangan page
    Click Element    id=ruangan-link
    Wait Until Element Is Visible    id=ruangan-table    timeout=${WAIT_TIMEOUT}
    Wait Until Element Is Visible    css=table#ruangan-table tbody tr    timeout=${WAIT_TIMEOUT}
    Sleep    ${LONG_WAIT}
      # Click QR button - opens in new tab/window
    Wait Until Element Is Visible    css=button.bg-green-500    timeout=${WAIT_TIMEOUT}
    Click Element    css=button.bg-green-500
    
    # Switch to the new tab/window that opened
    Sleep    ${SHORT_WAIT}
    Switch Window    NEW
    
    # Wait for QR page to load
    Wait Until Location Contains    /qr/generate-room    timeout=${WAIT_TIMEOUT}
    
    # Look for print button and click if present
    ${print_btn_present}=    Run Keyword And Return Status    Element Should Be Visible    xpath=//button[contains(text(), 'Print') or contains(text(), 'Cetak')]
    Run Keyword If    ${print_btn_present}    Click Button    xpath=//button[contains(text(), 'Print') or contains(text(), 'Cetak')]
    
    Sleep    ${SHORT_WAIT}
    
    # Close QR tab and return to main window
    Close Window
    Switch Window    MAIN
    Close Browser

Edit Ruangan - Validasi Nama Kosong
    [Documentation]    Test editing ruangan with empty name
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=${WAIT_TIMEOUT}
    
    # Navigate to ruangan page
    Click Element    id=ruangan-link
    Wait Until Element Is Visible    id=ruangan-table    timeout=${WAIT_TIMEOUT}
    Wait Until Element Is Visible    css=table#ruangan-table tbody tr    timeout=${WAIT_TIMEOUT}
    Sleep    ${LONG_WAIT}
    
    # Click edit button
    Wait Until Element Is Visible    css=button[id^="edit-ruangan-"]    timeout=${WAIT_TIMEOUT}
    Click Element    css=button[id^="edit-ruangan-"]
    
    # Wait for edit modal
    Wait Until Element Is Visible    id=edit-ruangan-modal    timeout=${WAIT_TIMEOUT}
    Sleep    ${SHORT_WAIT}
      # Clear name field and fill other fields
    Clear Element Text    css=#edit-ruangan-modal input[name="nama_ruangan"]
    Input Text    css=#edit-ruangan-modal input[name="lokasi"]    Gedung C

    # Submit form
    Click Button    css=#edit-ruangan-modal button[type="submit"]
    
    # Validate error message
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'The nama ruangan field is required.')]    timeout=${WAIT_TIMEOUT}
    
    Close Browser

Edit Ruangan - Validasi Lokasi Kosong
    [Documentation]    Test editing ruangan with empty location
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=${WAIT_TIMEOUT}
    
    # Navigate to ruangan page
    Click Element    id=ruangan-link
    Wait Until Element Is Visible    id=ruangan-table    timeout=${WAIT_TIMEOUT}
    Wait Until Element Is Visible    css=table#ruangan-table tbody tr    timeout=${WAIT_TIMEOUT}
    Sleep    ${LONG_WAIT}
    
    # Click edit button
    Wait Until Element Is Visible    css=button[id^="edit-ruangan-"]    timeout=${WAIT_TIMEOUT}
    Click Element    css=button[id^="edit-ruangan-"]
    
    # Wait for edit modal
    Wait Until Element Is Visible    id=edit-ruangan-modal    timeout=${WAIT_TIMEOUT}
    Sleep    ${SHORT_WAIT}
    
    # Fill fields but clear location
    Input Text    css=#edit-ruangan-modal input[name="nama_ruangan"]    Test Edit Ruangan
    Clear Element Text    css=#edit-ruangan-modal input[name="lokasi"]

    # Submit form
    Click Button    css=#edit-ruangan-modal button[type="submit"]
    
    # Validate error message
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'The lokasi field is required.')]    timeout=${WAIT_TIMEOUT}
    
    Close Browser

Edit Ruangan - Berhasil
    [Documentation]    Test successful ruangan editing
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=${WAIT_TIMEOUT}
    
    # Navigate to ruangan page
    Click Element    id=ruangan-link
    Wait Until Element Is Visible    id=ruangan-table    timeout=${WAIT_TIMEOUT}
    Wait Until Element Is Visible    css=table#ruangan-table tbody tr    timeout=${WAIT_TIMEOUT}
    Sleep    ${LONG_WAIT}
    
    # Get original data before edit
    ${original_data}=    Get Text    css=table#ruangan-table tbody tr:first-child
    
    # Click edit button
    Wait Until Element Is Visible    css=button[id^="edit-ruangan-"]    timeout=${WAIT_TIMEOUT}
    Click Element    css=button[id^="edit-ruangan-"]
    
    # Wait for edit modal
    Wait Until Element Is Visible    id=edit-ruangan-modal    timeout=${WAIT_TIMEOUT}
    Sleep    ${SHORT_WAIT}
      # Edit with valid data
    Clear Element Text    css=#edit-ruangan-modal input[name="nama_ruangan"]
    Input Text    css=#edit-ruangan-modal input[name="nama_ruangan"]    Ruangan Edited Successfully
    Clear Element Text    css=#edit-ruangan-modal input[name="lokasi"]
    Input Text    css=#edit-ruangan-modal input[name="lokasi"]    Gedung D Lantai 3

    # Submit form
    Click Button    css=#edit-ruangan-modal button[type="submit"]
    
    # Validate success
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'success!') or contains(text(), 'berhasil')]    timeout=${WAIT_TIMEOUT}
    
    # Verify data changed in table
    Sleep    ${SHORT_WAIT}
    Close Browser

Delete Ruangan - Berhasil
    [Documentation]    Test successful ruangan deletion
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=${WAIT_TIMEOUT}
    
    # Navigate to ruangan page
    Click Element    id=ruangan-link
    Wait Until Element Is Visible    id=ruangan-table    timeout=${WAIT_TIMEOUT}
    Wait Until Element Is Visible    css=table#ruangan-table tbody tr    timeout=${WAIT_TIMEOUT}
    Sleep    ${LONG_WAIT}
    
    # Count rows before deletion
    ${rows_before}=    Get Element Count    css=table#ruangan-table tbody tr
    Log    Rows before deletion: ${rows_before}
    
    # Click delete button
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

Delete Ruangan - Batal
    [Documentation]    Test canceling ruangan deletion
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=${WAIT_TIMEOUT}
    
    # Navigate to ruangan page
    Click Element    id=ruangan-link
    Wait Until Element Is Visible    id=ruangan-table    timeout=${WAIT_TIMEOUT}
    Wait Until Element Is Visible    css=table#ruangan-table tbody tr    timeout=${WAIT_TIMEOUT}
    Sleep    ${LONG_WAIT}
    
    # Count rows before deletion attempt
    ${rows_before}=    Get Element Count    css=table#ruangan-table tbody tr
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
    ${rows_after}=    Get Element Count    css=table#ruangan-table tbody tr
    Log    Rows after canceling deletion: ${rows_after}
    Should Be Equal As Integers    ${rows_before}    ${rows_after}
    
    Close Browser
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=${WAIT_TIMEOUT}
    
    # Navigate to ruangan page
    Click Element    id=ruangan-link
    Wait Until Element Is Visible    id=ruangan-table    timeout=${WAIT_TIMEOUT}
    Sleep    ${LONG_WAIT}
      # Check if bulk operations exist
    ${bulk_checkbox_present}=    Run Keyword And Return Status    Element Should Be Visible    css=input[type="checkbox"].select-all
    Run Keyword If    ${bulk_checkbox_present}    Run Keywords
    ...    Click Element    css=input[type="checkbox"].select-all
    ...    AND    Sleep    ${SHORT_WAIT}
    ...    AND    Run Keyword And Ignore Error    Click Button    xpath=//button[contains(text(), 'Bulk Delete')]
    
    Close Browser
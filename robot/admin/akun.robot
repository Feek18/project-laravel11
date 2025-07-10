*** Settings ***
Library    SeleniumLibrary

*** Variables ***
${BASE_URL}    http://localhost:8000
${LOGIN_URL}    http://localhost:8000/login
${AKUN_URL}    http://localhost:8000/akun
${BROWSER}      chrome
${EMAIL}     admin@gmail.com
${PASSWORD}     admin123
${NEW_EMAIL}       jack@gmail.com
${NEW_PASSWORD}    newpass
${WAIT_TIMEOUT}    15
${SHORT_WAIT}      2
${LONG_WAIT}       5

*** Test Cases ***

Buka Akun Page
    [Documentation]    Test opening akun page and verify table visibility
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=akun-link
    Wait Until Element Is Visible    id=akun-table    timeout=10
    Element Should Be Visible    id=akun-table
    Close Browser

Tambah Akun tanpa Email
    [Documentation]    Test adding akun without email - should show validation error
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=akun-link
    Wait Until Element Is Visible    id=akun-table    timeout=10
    Element Should Be Visible    id=akun-table
    Click Button    xpath=//button[contains(text(), 'Tambah') or @data-modal-target='authentication-modal']
    Wait Until Element Is Visible    id=authentication-modal    timeout=10
    Element Should Be Visible    id=authentication-modal
    # Input Text    id=email    test@example.com
    Input Text    id=password    123456
    Sleep    2s
    Click Button    xpath=//button[contains(text(), 'Add')]
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'email') and contains(text(), 'required')]    timeout=10
    Close Browser

Tambah Akun tanpa Password
    [Documentation]    Test adding akun without password - should show validation error
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=akun-link
    Wait Until Element Is Visible    id=akun-table    timeout=10
    Element Should Be Visible    id=akun-table
    Click Button    xpath=//button[contains(text(), 'Tambah') or @data-modal-target='authentication-modal']
    Wait Until Element Is Visible    id=authentication-modal    timeout=10
    Element Should Be Visible    id=authentication-modal
    Input Text    id=email    testuser@example.com
    # Input Text    id=password    123456
    Sleep    2s
    Click Button    xpath=//button[contains(text(), 'Add')]
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'password') and contains(text(), 'required')]    timeout=10
    Close Browser

Tambah Akun dengan Email Invalid
    [Documentation]    Test adding akun with invalid email format - should show validation error
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=akun-link
    Wait Until Element Is Visible    id=akun-table    timeout=10
    Element Should Be Visible    id=akun-table
    Click Button    xpath=//button[contains(text(), 'Tambah') or @data-modal-target='authentication-modal']
    Wait Until Element Is Visible    id=authentication-modal    timeout=10
    Element Should Be Visible    id=authentication-modal
    Input Text    id=email    invalid-email-format
    Input Text    id=password    123456
    Sleep    2s
    Click Button    xpath=//button[contains(text(), 'Add')]
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'valid email')]    timeout=10
    Close Browser

Tambah Akun dengan Email yang Sudah Ada
    [Documentation]    Test adding akun with duplicate email - should show validation error
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=akun-link
    Wait Until Element Is Visible    id=akun-table    timeout=10
    Element Should Be Visible    id=akun-table
    Click Button    xpath=//button[contains(text(), 'Tambah') or @data-modal-target='authentication-modal']
    Wait Until Element Is Visible    id=authentication-modal    timeout=10
    Element Should Be Visible    id=authentication-modal
    Input Text    id=email    admin@gmail.com
    Input Text    id=password    123456
    Sleep    2s
    Click Button    xpath=//button[contains(text(), 'Add')]
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'email has already been taken') or contains(text(), 'already exists') or contains(text(), 'sudah ada')]    timeout=10
    Close Browser

Tambah Akun - Berhasil
    [Documentation]    Test successful akun addition
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Maximize Browser Window

    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button

    Wait Until Element Is Visible    id=dashboard    timeout=10

    Click Element    id=akun-link
    Wait Until Element Is Visible    id=akun-table    timeout=10

    Click Button    xpath=//button[contains(text(), 'Tambah') or @data-modal-target='authentication-modal']
    Wait Until Element Is Visible    id=authentication-modal    timeout=10

    Input Text    id=email    ${NEW_EMAIL}
    Input Text    id=password    ${NEW_PASSWORD}

    Sleep    1s
    Click Button    xpath=//button[contains(text(), 'Add')]

    # Tunggu modal tertutup dan data dimuat ulang
    Wait Until Element Is Not Visible    id=authentication-modal    timeout=10
    Sleep    2s
    Reload Page

    Wait Until Element Is Visible    id=akun-table    timeout=10

    # Cari user berdasarkan email (jika pakai fitur pencarian di tabel)
    Input Text    css=input[type="search"]    ${NEW_EMAIL}
    Sleep    1s

    # Verifikasi email muncul di tabel
    Wait Until Page Contains    ${NEW_EMAIL}    timeout=10

    Capture Page Screenshot
    Close Browser

Delete Akun - Berhasil
    [Documentation]    Test successful akun deletion
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=${WAIT_TIMEOUT}
    
    # Navigate to akun page
    Click Element    id=akun-link
    Wait Until Element Is Visible    id=akun-table    timeout=${WAIT_TIMEOUT}
    Wait Until Element Is Visible    css=table#akun-table tbody tr    timeout=${WAIT_TIMEOUT}
    Sleep    ${LONG_WAIT}
    
    # Count rows before deletion
    ${rows_before}=    Get Element Count    css=table#akun-table tbody tr
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

Delete Akun - Batal
    [Documentation]    Test canceling akun deletion
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=${WAIT_TIMEOUT}
    
    # Navigate to akun page
    Click Element    id=akun-link
    Wait Until Element Is Visible    id=akun-table    timeout=${WAIT_TIMEOUT}
    Wait Until Element Is Visible    css=table#akun-table tbody tr    timeout=${WAIT_TIMEOUT}
    Sleep    ${LONG_WAIT}
    
    # Count rows before deletion attempt
    ${rows_before}=    Get Element Count    css=table#akun-table tbody tr
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
    ${rows_after}=    Get Element Count    css=table#akun-table tbody tr
    Log    Rows after canceling deletion: ${rows_after}
    Should Be Equal As Integers    ${rows_before}    ${rows_after}
    
    Close Browser
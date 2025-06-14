*** Settings ***
Library    SeleniumLibrary

*** Variables ***
${BASE_URL}    http://localhost:8000
${LOGIN_URL}    http://localhost:8000/login
${BROWSER}      chrome
${EMAIL}     admin@gmail.com
${PASSWORD}     admin123
${WAIT_TIMEOUT}    15
${SHORT_WAIT}      2
${LONG_WAIT}       5

*** Test Cases ***

Buka Matkul Page
    [Documentation] 
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=matkul-link
    Wait Until Element Is Visible    id=matkul-table    timeout=10
    Element Should Be Visible    id=matkul-table
    Close Browser

Tambah Matkul tanpa Nama
    [Documentation] 
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=matkul-link
    Wait Until Element Is Visible    id=matkul-table    timeout=10
    Element Should Be Visible    id=matkul-table
    Click Button    xpath=//button[contains(text(), 'Tambah') or @data-modal-target='matkul-modal']
    Wait Until Element Is Visible    id=matkul-modal    timeout=10
    Element Should Be Visible    id=matkul-modal
    # Input Text    id=nama    Test Mata Kuliah
    Input Text    id=kode_matkul    TES001
    Input Text    id=semester    3
    Sleep    2s
    Click Button    id=matkul-submit-btn
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'The mata kuliah field is required.')]    timeout=10
    Close Browser

Tambah Matkul tanpa Kode
    [Documentation] 
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=matkul-link
    Wait Until Element Is Visible    id=matkul-table    timeout=10
    Element Should Be Visible    id=matkul-table
    Click Button    xpath=//button[contains(text(), 'Tambah') or @data-modal-target='matkul-modal']
    Wait Until Element Is Visible    id=matkul-modal    timeout=10
    Element Should Be Visible    id=matkul-modal
    Input Text    id=mata_kuliah    Test Mata Kuliah
    # Input Text    id=kode_matkul    TES001
    Input Text    id=semester    3
    Sleep    2s
    Click Button    id=matkul-submit-btn
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'The kode matkul field is required.')]    timeout=10
    Close Browser

Tambah Matkul tanpa Semester
    [Documentation] 
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=matkul-link
    Wait Until Element Is Visible    id=matkul-table    timeout=10
    Element Should Be Visible    id=matkul-table
    Click Button    xpath=//button[contains(text(), 'Tambah') or @data-modal-target='matkul-modal']
    Wait Until Element Is Visible    id=matkul-modal    timeout=10
    Element Should Be Visible    id=matkul-modal
    Input Text    id=mata_kuliah    Test Mata Kuliah
    Input Text    id=kode_matkul    TES001
    # Input Text    id=semester    3
    Sleep    2s
    Click Button    id=matkul-submit-btn
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'The semester field is required.')]    timeout=10
    Close Browser

Tambah Matkul dengan Semester Invalid
    [Documentation] 
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=matkul-link
    Wait Until Element Is Visible    id=matkul-table    timeout=10
    Element Should Be Visible    id=matkul-table
    Click Button    xpath=//button[contains(text(), 'Tambah') or @data-modal-target='matkul-modal']
    Wait Until Element Is Visible    id=matkul-modal    timeout=10
    Element Should Be Visible    id=matkul-modal
    Input Text    id=mata_kuliah    Test Mata Kuliah
    Input Text    id=kode_matkul    TES001
    Input Text    id=semester    0
    Sleep    2s
    Click Button    id=matkul-submit-btn
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'must be') or contains(text(), 'invalid')]    timeout=10
    Close Browser

# Tambah Matkul dengan Kode Duplikat
#     [Documentation] 
#     Open Browser    ${LOGIN_URL}    ${BROWSER}
#     Input Text    id=email    ${EMAIL}
#     Input Text    id=password    ${PASSWORD}
#     Click Button    id=login-button
#     Wait Until Element Is Visible    id=dashboard    timeout=10
#     Element Should Be Visible    id=dashboard
#     Click Element    id=matkul-link
#     Wait Until Element Is Visible    id=matkul-table    timeout=10
#     Element Should Be Visible    id=matkul-table
    
#     # Add first matkul
#     Click Button    xpath=//button[contains(text(), 'Tambah') or @data-modal-target='matkul-modal']
#     Wait Until Element Is Visible    id=matkul-modal    timeout=10
#     Input Text    id=nama    Mata Kuliah Pertama
#     Input Text    id=kode    DUPLIKAT001
#     Input Text    id=sks    3
#     Click Button    id=matkul-submit-btn
#     Wait Until Element Is Visible    xpath=//*[contains(text(), 'success!')]    timeout=10
    
#     # Try to add second matkul with same kode
#     Click Button    xpath=//button[contains(text(), 'Tambah') or @data-modal-target='matkul-modal']
#     Wait Until Element Is Visible    id=matkul-modal    timeout=10
#     Input Text    id=nama    Mata Kuliah Kedua
#     Input Text    id=kode    DUPLIKAT001
#     Input Text    id=sks    2
#     Sleep    2s
#     Click Button    id=matkul-submit-btn
#     Wait Until Element Is Visible    xpath=//*[contains(text(), 'already taken') or contains(text(), 'sudah ada')]    timeout=10
#     Close Browser

Tambah Matkul
    [Documentation] 
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=matkul-link
    Wait Until Element Is Visible    id=matkul-table    timeout=10
    Element Should Be Visible    id=matkul-table
    Click Button    xpath=//button[contains(text(), 'Tambah') or @data-modal-target='matkul-modal']
    Wait Until Element Is Visible    id=matkul-modal    timeout=10
    Element Should Be Visible    id=matkul-modal
    Input Text    id=mata_kuliah    Pemrograman Web
    Input Text    id=kode_matkul    PWB001
    Input Text    id=semester    3
    Sleep    2s
    Click Button    id=matkul-submit-btn
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'success!')]    timeout=10
    Close Browser

Edit Matkul - Validasi Nama Kosong
    [Documentation]    Test editing matkul with empty name
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=${WAIT_TIMEOUT}
    
    # Navigate to matkul page
    Click Element    id=matkul-link
    Wait Until Element Is Visible    id=matkul-table    timeout=${WAIT_TIMEOUT}
    Wait Until Element Is Visible    css=table#matkul-table tbody tr    timeout=${WAIT_TIMEOUT}
    Sleep    ${LONG_WAIT}
    
    # Click edit button
    Wait Until Element Is Visible    css=button[id^="edit-matkul-"]    timeout=${WAIT_TIMEOUT}
    Click Element    css=button[id^="edit-matkul-"]
    
    # Wait for edit modal
    Wait Until Element Is Visible    id=edit-matkul-modal    timeout=${WAIT_TIMEOUT}
    Sleep    ${SHORT_WAIT}
    
    # Clear name field
    Clear Element Text    css=#edit-matkul-modal input[name="mata_kuliah"]
    Input Text    css=#edit-matkul-modal input[name="kode_matkul"]    EDIT001
    Input Text    css=#edit-matkul-modal input[name="semester"]    3

    # Submit form
    Click Button    css=#edit-matkul-modal button[type="submit"]
    
    # Validate error message
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'The mata kuliah field is required.')]    timeout=${WAIT_TIMEOUT}
    
    Close Browser

Edit Matkul - Validasi Semester Invalid
    [Documentation]    Test editing matkul with invalid Semester
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=${WAIT_TIMEOUT}
    
    # Navigate to matkul page
    Click Element    id=matkul-link
    Wait Until Element Is Visible    id=matkul-table    timeout=${WAIT_TIMEOUT}
    Wait Until Element Is Visible    css=table#matkul-table tbody tr    timeout=${WAIT_TIMEOUT}
    Sleep    ${LONG_WAIT}
    
    # Click edit button
    Wait Until Element Is Visible    css=button[id^="edit-matkul-"]    timeout=${WAIT_TIMEOUT}
    Click Element    css=button[id^="edit-matkul-"]
    
    # Wait for edit modal
    Wait Until Element Is Visible    id=edit-matkul-modal    timeout=${WAIT_TIMEOUT}
    Sleep    ${SHORT_WAIT}
    
    # Fill with invalid Semester
    Input Text    css=#edit-matkul-modal input[name="mata_kuliah"]    Test Edit Matkul
    Input Text    css=#edit-matkul-modal input[name="kode_matkul"]    EDIT001
    Clear Element Text    css=#edit-matkul-modal input[name="semester"]
    Input Text    css=#edit-matkul-modal input[name="semester"]    -1

    # Submit form
    Click Button    css=#edit-matkul-modal button[type="submit"]
    
    # Validate error message
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'must be') or contains(text(), 'invalid')]    timeout=${WAIT_TIMEOUT}
    
    Close Browser

Edit Matkul - Berhasil
    [Documentation]    Test successful matkul editing
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=${WAIT_TIMEOUT}
    
    # Navigate to matkul page
    Click Element    id=matkul-link
    Wait Until Element Is Visible    id=matkul-table    timeout=${WAIT_TIMEOUT}
    Wait Until Element Is Visible    css=table#matkul-table tbody tr    timeout=${WAIT_TIMEOUT}
    Sleep    ${LONG_WAIT}
    
    # Get original data before edit
    ${original_data}=    Get Text    css=table#matkul-table tbody tr:first-child
    
    # Click edit button
    Wait Until Element Is Visible    css=button[id^="edit-matkul-"]    timeout=${WAIT_TIMEOUT}
    Click Element    css=button[id^="edit-matkul-"]
    
    # Wait for edit modal
    Wait Until Element Is Visible    id=edit-matkul-modal    timeout=${WAIT_TIMEOUT}
    Sleep    ${SHORT_WAIT}
    
    # Edit with valid data
    Clear Element Text    css=#edit-matkul-modal input[name="mata_kuliah"]
    Input Text    css=#edit-matkul-modal input[name="mata_kuliah"]    Matkul Edited Successfully
    Clear Element Text    css=#edit-matkul-modal input[name="kode_matkul"]
    Input Text    css=#edit-matkul-modal input[name="kode_matkul"]    EDITED001
    Clear Element Text    css=#edit-matkul-modal input[name="semester"]
    Input Text    css=#edit-matkul-modal input[name="semester"]    4

    # Submit form
    Click Button    css=#edit-matkul-modal button[type="submit"]
    
    # Validate success
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'success!') or contains(text(), 'berhasil')]    timeout=${WAIT_TIMEOUT}
    
    # Verify data changed in table
    Sleep    ${SHORT_WAIT}
    Close Browser

Delete Matkul - Berhasil
    [Documentation]    Test successful matkul deletion
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=${WAIT_TIMEOUT}
    
    # Navigate to matkul page
    Click Element    id=matkul-link
    Wait Until Element Is Visible    id=matkul-table    timeout=${WAIT_TIMEOUT}
    Wait Until Element Is Visible    css=table#matkul-table tbody tr    timeout=${WAIT_TIMEOUT}
    Sleep    ${LONG_WAIT}
    
    # Count rows before deletion
    ${rows_before}=    Get Element Count    css=table#matkul-table tbody tr
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

Delete Matkul - Batal
    [Documentation]    Test canceling matkul deletion
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=${WAIT_TIMEOUT}
    
    # Navigate to matkul page
    Click Element    id=matkul-link
    Wait Until Element Is Visible    id=matkul-table    timeout=${WAIT_TIMEOUT}
    Wait Until Element Is Visible    css=table#matkul-table tbody tr    timeout=${WAIT_TIMEOUT}
    Sleep    ${LONG_WAIT}
    
    # Count rows before deletion attempt
    ${rows_before}=    Get Element Count    css=table#matkul-table tbody tr
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
    ${rows_after}=    Get Element Count    css=table#matkul-table tbody tr
    Log    Rows after canceling deletion: ${rows_after}
    Should Be Equal As Integers    ${rows_before}    ${rows_after}
    
    Close Browser

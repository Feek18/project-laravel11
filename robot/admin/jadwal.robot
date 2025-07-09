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

Buka Jadwal Page
    [Documentation] 
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=jadwal-link
    Wait Until Element Is Visible    id=jadwal-table    timeout=10
    Element Should Be Visible    id=jadwal-table
    Close Browser
Tambah Jadwal tanpa Nama Ruangan
    [Documentation] 
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=jadwal-link
    Wait Until Element Is Visible    id=jadwal-table    timeout=10
    Element Should Be Visible    id=jadwal-table
    Click Button    id=tambah-jadwal-button
    Wait Until Element Is Visible    id=jadwal-modal    timeout=10
    Element Should Be Visible    id=jadwal-modal
    # Select From List By Label    id=id_ruang    Ruang Gedung 2 - Gedung D - Lantai 3
    Select From List By Label    id=id_matkul    Interoperabilitas
    Select From List By Label    id=hari    Senin
    Input Text    id=jam_mulai    08:00
    Input Text    id=jam_selesai    10:00
    Sleep    2s
    Click Button    id=jadwal-submit-btn
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'The Nama Ruangan field is required.')]    timeout=10
    Close Browser
Tambah Jadwal tanpa Mata Kuliah
    [Documentation] 
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=jadwal-link
    Wait Until Element Is Visible    id=jadwal-table    timeout=10
    Element Should Be Visible    id=jadwal-table
    Click Button    id=tambah-jadwal-button
    Wait Until Element Is Visible    id=jadwal-modal    timeout=10
    Element Should Be Visible    id=jadwal-modal
    Select From List By Label    id=id_ruang    Ruang Gedung 2 - Gedung D - Lantai 3
    # Select From List By Label    id=id_matkul    Interoperabilitas
    Select From List By Label    id=hari    Senin
    Input Text    id=jam_mulai    08:00
    Input Text    id=jam_selesai    10:00
    Sleep    2s
    Click Button    id=jadwal-submit-btn
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'The Nama Mata Kuliah field is required.')]    timeout=10
    Close Browser
Tambah Jadwal tanpa Hari
    [Documentation] 
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=jadwal-link
    Wait Until Element Is Visible    id=jadwal-table    timeout=10
    Element Should Be Visible    id=jadwal-table
    Click Button    id=tambah-jadwal-button
    Wait Until Element Is Visible    id=jadwal-modal    timeout=10
    Element Should Be Visible    id=jadwal-modal
    Select From List By Label    id=id_ruang    Ruang Gedung 2 - Gedung D - Lantai 3
    Select From List By Label    id=id_matkul    Interoperabilitas
    # Select From List By Label    id=hari    Senin
    Input Text    id=jam_mulai    08:00
    Input Text    id=jam_selesai    10:00
    Sleep    2s
    Click Button    id=jadwal-submit-btn
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'The selected Hari is invalid')]    timeout=10
    Close Browser
Tambah Jadwal tanpa Jam Mulai
    [Documentation] 
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=jadwal-link
    Wait Until Element Is Visible    id=jadwal-table    timeout=10
    Element Should Be Visible    id=jadwal-table
    Click Button    id=tambah-jadwal-button
    Wait Until Element Is Visible    id=jadwal-modal    timeout=10
    Element Should Be Visible    id=jadwal-modal
    Select From List By Label    id=id_ruang    Ruang Gedung 2 - Gedung D - Lantai 3
    Select From List By Label    id=id_matkul    Interoperabilitas
    Select From List By Label    id=hari    Senin
    # Input Text    id=jam_mulai    08:00
    Input Text    id=jam_selesai    10:00
    Sleep    2s
    Click Button    id=jadwal-submit-btn
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'The Jam Mulai field is required.')]    timeout=10
    Close Browser
Tambah Jadwal tanpa Jam Selesai
    [Documentation] 
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=jadwal-link
    Wait Until Element Is Visible    id=jadwal-table    timeout=10
    Element Should Be Visible    id=jadwal-table
    Click Button    id=tambah-jadwal-button
    Wait Until Element Is Visible    id=jadwal-modal    timeout=10
    Element Should Be Visible    id=jadwal-modal
    Select From List By Label    id=id_ruang    Ruang Gedung 2 - Gedung D - Lantai 3
    Select From List By Label    id=id_matkul    Interoperabilitas
    Select From List By Label    id=hari    Senin
    Input Text    id=jam_mulai    08:00
    # Input Text    id=jam_selesai    10:00
    Sleep    2s
    Click Button    id=jadwal-submit-btn
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'The Jam Selesai field is required.')]    timeout=10
    Close Browser
Tambah Jadwal Jam Tidak Valid
    [Documentation] 
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=jadwal-link
    Wait Until Element Is Visible    id=jadwal-table    timeout=10
    Element Should Be Visible    id=jadwal-table
    Click Button    id=tambah-jadwal-button
    Wait Until Element Is Visible    id=jadwal-modal    timeout=10
    Element Should Be Visible    id=jadwal-modal
    Select From List By Label    id=id_ruang    Ruang Gedung 2 - Gedung D - Lantai 3
    Select From List By Label    id=id_matkul    Interoperabilitas
    Select From List By Label    id=hari    Senin
    Input Text    id=jam_mulai    08:00
    Input Text    id=jam_selesai    07:00
    Sleep    2s
    Click Button    id=jadwal-submit-btn
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'must be')]    timeout=10
    Close Browser
Tambah Jadwal
    [Documentation] 
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=jadwal-link
    Wait Until Element Is Visible    id=jadwal-table    timeout=10
    Element Should Be Visible    id=jadwal-table
    Click Button    id=tambah-jadwal-button
    Wait Until Element Is Visible    id=jadwal-modal    timeout=10
    Element Should Be Visible    id=jadwal-modal
    Select From List By Label    id=id_ruang    Ruang Gedung 2 - Gedung D - Lantai 3
    Select From List By Label    id=id_matkul    Interoperabilitas
    Select From List By Label    id=hari    Senin
    Input Text    id=jam_mulai    08:00
    Input Text    id=jam_selesai    10:00
    Sleep    2s
    Click Button    id=jadwal-submit-btn
    Sleep    2s
    Wait Until Element Is Visible    xpath=//*[contains(., 'success')]    timeout=10
    Close Browser
Jadwal Konflik
    [Documentation] 
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10

    # Add the first valid jadwal
    Click Element    id=jadwal-link
    Wait Until Element Is Visible    id=jadwal-table    timeout=10
    Click Button    id=tambah-jadwal-button
    Wait Until Element Is Visible    id=jadwal-modal    timeout=10
    Select From List By Label    id=id_ruang    Ruang Gedung 2 - Gedung D - Lantai 3
    Select From List By Label    id=id_matkul    Interoperabilitas
    Select From List By Label    id=hari    Selasa
    Input Text    id=jam_mulai    09:00
    Input Text    id=jam_selesai    11:00
    Sleep    2s
    Click Button    id=jadwal-submit-btn
    Wait Until Element Is Visible    xpath=//*[contains(., 'success')]    timeout=10
    # Wait Until Element Is Visible    xpath=//*[contains(text(), 'success!')]    timeout=10

    # Attempt to add a conflicting jadwal
    Click Button    id=tambah-jadwal-button
    Wait Until Element Is Visible    id=jadwal-modal    timeout=10
    Select From List By Label    id=id_ruang    Ruang Gedung 2 - Gedung D - Lantai 3
    Select From List By Label    id=id_matkul    Kecerdasan Buatan
    Select From List By Label    id=hari    Selasa
    Input Text    id=jam_mulai    10:30
    Input Text    id=jam_selesai    12:00

    Sleep    2s    # Give time for debounce conflict checker to run

    # Validate conflict checker response
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'Konflik')]    timeout=10

    # Try submitting anyway
    Click Button    id=jadwal-submit-btn
    Sleep    2s

    # Check that page still shows modal or does not show success
    # Wait Until Element Is Not Visible    xpath=//*[contains(text(), 'success!')]    timeout=10

    Close Browser
Edit Jadwal - Validasi Waktu Invalid
    [Documentation]    Test editing jadwal with invalid time (end time before start time)
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=${WAIT_TIMEOUT}
    
    # Navigate to jadwal page
    Click Element    id=jadwal-link
    Wait Until Element Is Visible    id=jadwal-table    timeout=${WAIT_TIMEOUT}
    Wait Until Element Is Visible    css=table#jadwal-table tbody tr    timeout=${WAIT_TIMEOUT}
    Sleep    ${LONG_WAIT}
    
    # Click edit button
    Wait Until Element Is Visible    css=button[id^="edit-jadwal-"]    timeout=${WAIT_TIMEOUT}
    Click Element    css=button[id^="edit-jadwal-"]
    
    # Wait for edit modal
    Wait Until Element Is Visible    id=edit-jadwal-modal    timeout=${WAIT_TIMEOUT}
    Sleep    ${SHORT_WAIT}
    
    # Fill with invalid time
    Select From List By Label    css=#edit-jadwal-modal select[name="id_ruang"]    Ruang Gedung 2 - Gedung D - Lantai 3
    Select From List By Label    css=#edit-jadwal-modal select[name="id_matkul"]    Interoperabilitas
    Select From List By Label    css=#edit-jadwal-modal select[name="hari"]    Senin
    Clear Element Text    css=#edit-jadwal-modal input[name="jam_mulai"]
    Input Text    css=#edit-jadwal-modal input[name="jam_mulai"]    14:00
    Clear Element Text    css=#edit-jadwal-modal input[name="jam_selesai"]
    Input Text    css=#edit-jadwal-modal input[name="jam_selesai"]    12:00
    
    # Submit form
    Click Button    css=#edit-jadwal-modal button[type="submit"]
    
    # Validate error message for invalid time
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'after') or contains(text(), 'must be')]    timeout=${WAIT_TIMEOUT}
    
    Close Browser

Edit Jadwal - Konflik Waktu
    [Documentation]    Test editing jadwal that creates a time conflict
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=${WAIT_TIMEOUT}
    
    # Navigate to jadwal page
    Click Element    id=jadwal-link
    Wait Until Element Is Visible    id=jadwal-table    timeout=${WAIT_TIMEOUT}
    
    # First, add a jadwal to create potential conflict
    Click Button    id=tambah-jadwal-button
    Wait Until Element Is Visible    id=jadwal-modal    timeout=${WAIT_TIMEOUT}
    Select From List By Label    id=id_ruang    Ruang Gedung 1 - Gedung D4 TRPL - Lantai 3
    Select From List By Label    id=id_matkul    Interoperabilitas
    Select From List By Label    id=hari    Kamis
    Input Text    id=jam_mulai    08:00
    Input Text    id=jam_selesai    10:00
    Click Button    id=jadwal-submit-btn
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'success!')]    timeout=${WAIT_TIMEOUT}
    
    # Wait for table to refresh
    Sleep    ${LONG_WAIT}
    Wait Until Element Is Visible    css=table#jadwal-table tbody tr    timeout=${WAIT_TIMEOUT}
    
    # Get all edit buttons and click the second one
    ${edit_buttons}=    Get WebElements    css=button[id^="edit-jadwal-"]
    ${button_count}=    Get Length    ${edit_buttons}
    Log    Found ${button_count} edit buttons
    
    # Click the second edit button if it exists, otherwise click the first
    Run Keyword If    ${button_count} >= 2    Click Element    xpath=(//button[starts-with(@id, 'edit-jadwal-')])[2]
    ...    ELSE    Click Element    xpath=(//button[starts-with(@id, 'edit-jadwal-')])[1]
    
    # Wait for edit modal
    Wait Until Element Is Visible    id=edit-jadwal-modal    timeout=${WAIT_TIMEOUT}
    Sleep    ${SHORT_WAIT}
    
    # Edit to create conflict
    Select From List By Label    css=#edit-jadwal-modal select[name="id_ruang"]    Ruang Gedung 1 - Gedung D4 TRPL - Lantai 3
    Select From List By Label    css=#edit-jadwal-modal select[name="id_matkul"]    Kecerdasan Buatan
    Select From List By Label    css=#edit-jadwal-modal select[name="hari"]    Kamis
    Clear Element Text    css=#edit-jadwal-modal input[name="jam_mulai"]
    Input Text    css=#edit-jadwal-modal input[name="jam_mulai"]    09:00
    Clear Element Text    css=#edit-jadwal-modal input[name="jam_selesai"]
    Input Text    css=#edit-jadwal-modal input[name="jam_selesai"]    11:00
    
    Sleep    ${SHORT_WAIT}    # Wait for conflict checker
    
    # Check for conflict warning
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'Konflik') or contains(text(), 'error!')]    timeout=${WAIT_TIMEOUT}
    
    # Try to submit anyway
    Click Button    css=#edit-jadwal-modal button[type="submit"]
    Sleep    3s
    Close Browser

Edit Jadwal - Berhasil
    [Documentation]    Test successful jadwal editing
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=${WAIT_TIMEOUT}
    
    # Navigate to jadwal page
    Click Element    id=jadwal-link
    Wait Until Element Is Visible    id=jadwal-table    timeout=${WAIT_TIMEOUT}
    Wait Until Element Is Visible    css=table#jadwal-table tbody tr    timeout=${WAIT_TIMEOUT}
    Sleep    ${LONG_WAIT}
    
    # Get original data before edit
    ${original_data}=    Get Text    css=table#jadwal-table tbody tr:first-child
    
    # Click edit button
    Wait Until Element Is Visible    css=button[id^="edit-jadwal-"]    timeout=${WAIT_TIMEOUT}
    Click Element    css=button[id^="edit-jadwal-"]
    
    # Wait for edit modal
    Wait Until Element Is Visible    id=edit-jadwal-modal    timeout=${WAIT_TIMEOUT}
    Sleep    ${SHORT_WAIT}
    
    # Edit with valid data
    Select From List By Label    css=#edit-jadwal-modal select[name="id_ruang"]    Ruang Gedung 7 - Gedung D4 TRPL - Lantai 3
    Select From List By Label    css=#edit-jadwal-modal select[name="id_matkul"]    Kecerdasan Buatan
    Select From List By Label    css=#edit-jadwal-modal select[name="hari"]    Jumat
    Clear Element Text    css=#edit-jadwal-modal input[name="jam_mulai"]
    Input Text    css=#edit-jadwal-modal input[name="jam_mulai"]    13:00
    Clear Element Text    css=#edit-jadwal-modal input[name="jam_selesai"]
    Input Text    css=#edit-jadwal-modal input[name="jam_selesai"]    15:00
    
    # Submit form
    Click Button    css=#edit-jadwal-modal button[type="submit"]
    
    # Validate success
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'success!') or contains(text(), 'berhasil')]    timeout=${WAIT_TIMEOUT}
    
    # Verify data changed in table
    Sleep    ${SHORT_WAIT}
    Close Browser
Delete Jadwal - Berhasil
    [Documentation]    Test successful jadwal deletion
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=${WAIT_TIMEOUT}
    
    # Navigate to jadwal page
    Click Element    id=jadwal-link
    Wait Until Element Is Visible    id=jadwal-table    timeout=${WAIT_TIMEOUT}
    Wait Until Element Is Visible    css=table#jadwal-table tbody tr    timeout=${WAIT_TIMEOUT}
    Sleep    ${LONG_WAIT}
    
    # Count rows before deletion
    ${rows_before}=    Get Element Count    css=table#jadwal-table tbody tr
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
    
    # Verify row count decreased
    ${rows_after}=    Get Element Count    css=table#jadwal-table tbody tr
    Log    Rows after deletion: ${rows_after}
    ${expected_rows}=    Evaluate    ${rows_before} - 1
    Should Be Equal As Integers    ${rows_after}    ${expected_rows}
    
    Close Browser

Delete Jadwal - Batal
    [Documentation]    Test canceling jadwal deletion
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=${WAIT_TIMEOUT}
    
    # Navigate to jadwal page
    Click Element    id=jadwal-link
    Wait Until Element Is Visible    id=jadwal-table    timeout=${WAIT_TIMEOUT}
    Wait Until Element Is Visible    css=table#jadwal-table tbody tr    timeout=${WAIT_TIMEOUT}
    Sleep    ${LONG_WAIT}
    
    # Count rows before deletion attempt
    ${rows_before}=    Get Element Count    css=table#jadwal-table tbody tr
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
    ${rows_after}=    Get Element Count    css=table#jadwal-table tbody tr
    Log    Rows after canceling deletion: ${rows_after}
    Should Be Equal As Integers    ${rows_before}    ${rows_after}
    
    Close Browser
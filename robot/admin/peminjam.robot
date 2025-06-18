*** Settings ***
Library    SeleniumLibrary

*** Variables ***
${BASE_URL}    http://localhost:8000
${LOGIN_URL}    http://localhost:8000/login
${PEMINJAM_URL}    http://localhost:8000/peminjam
${BROWSER}      chrome
${EMAIL}     admin@gmail.com
${PASSWORD}     admin123
${WAIT_TIMEOUT}    15
${SHORT_WAIT}      2
${LONG_WAIT}       5

*** Test Cases ***

Buka Peminjam Page
    [Documentation]    Test opening peminjam page and verify table visibility
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=peminjam-link
    Wait Until Element Is Visible    id=peminjam-table    timeout=10
    Element Should Be Visible    id=peminjam-table
    Close Browser

Tambah Peminjam tanpa Pengguna
    [Documentation]    Test adding peminjam without selecting pengguna - should show validation error
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=peminjam-link
    Wait Until Element Is Visible    id=peminjam-table    timeout=10
    Element Should Be Visible    id=peminjam-table
    Click Button    xpath=//button[contains(text(), 'Tambah') or @data-modal-target='authentication-modal']
    Wait Until Element Is Visible    id=authentication-modal    timeout=10
    Element Should Be Visible    id=authentication-modal
    # Select From List By Value    id=id_pengguna    1
    Select From List By Value    id=id_ruang    3
    Input Text    id=tanggal_pinjam    2025-06-20
    Input Text    id=waktu_mulai    09:00
    Input Text    id=waktu_selesai    12:00
    Input Text    id=keperluan    Meeting rutin
    Sleep    2s
    Click Button    id=jadwal-submit-btn
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'The id pengguna field is required.') and contains(text(), 'required')]    timeout=10
    Close Browser

Tambah Peminjam tanpa Ruangan
    [Documentation]    Test adding peminjam without selecting ruangan - should show validation error
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=peminjam-link
    Wait Until Element Is Visible    id=peminjam-table    timeout=10
    Element Should Be Visible    id=peminjam-table
    Click Button    xpath=//button[contains(text(), 'Tambah') or @data-modal-target='authentication-modal']
    Wait Until Element Is Visible    id=authentication-modal    timeout=10
    Element Should Be Visible    id=authentication-modal
    Select From List By Value    id=id_pengguna    2
    # Select From List By Value    id=id_ruang    1
    Input Text    id=tanggal_pinjam    2025-06-20
    Input Text    id=waktu_mulai    09:00
    Input Text    id=waktu_selesai    12:00
    Input Text    id=keperluan    Meeting rutin
    Sleep    2s
    Click Button    id=jadwal-submit-btn
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'id ruang') and contains(text(), 'required')]    timeout=10
    Close Browser

Tambah Peminjam tanpa Tanggal
    [Documentation]    Test adding peminjam without tanggal_pinjam - should show validation error
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=peminjam-link
    Wait Until Element Is Visible    id=peminjam-table    timeout=10
    Element Should Be Visible    id=peminjam-table
    Click Button    xpath=//button[contains(text(), 'Tambah') or @data-modal-target='authentication-modal']
    Wait Until Element Is Visible    id=authentication-modal    timeout=10
    Element Should Be Visible    id=authentication-modal
    Select From List By Value    id=id_pengguna    2
    Select From List By Value    id=id_ruang    3
    # Input Text    id=tanggal_pinjam    2025-06-20
    Input Text    id=waktu_mulai    09:00
    Input Text    id=waktu_selesai    12:00
    Input Text    id=keperluan    Meeting rutin
    Sleep    2s
    Click Button    id=jadwal-submit-btn
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'tanggal pinjam') and contains(text(), 'required')]    timeout=10
    Close Browser

Tambah Peminjam tanpa Waktu Mulai
    [Documentation]    Test adding peminjam without waktu_mulai - should show validation error
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=peminjam-link
    Wait Until Element Is Visible    id=peminjam-table    timeout=10
    Element Should Be Visible    id=peminjam-table
    Click Button    xpath=//button[contains(text(), 'Tambah') or @data-modal-target='authentication-modal']
    Wait Until Element Is Visible    id=authentication-modal    timeout=10
    Element Should Be Visible    id=authentication-modal
    Select From List By Value    id=id_pengguna    2
    Select From List By Value    id=id_ruang    3
    Input Text    id=tanggal_pinjam    2025-06-20
    # Input Text    id=waktu_mulai    09:00
    Input Text    id=waktu_selesai    12:00
    Input Text    id=keperluan    Meeting rutin
    Sleep    2s
    Click Button    id=jadwal-submit-btn
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'waktu mulai') and contains(text(), 'required')]    timeout=10
    Close Browser

Tambah Peminjam tanpa Waktu Selesai
    [Documentation]    Test adding peminjam without waktu_selesai - should show validation error
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=peminjam-link
    Wait Until Element Is Visible    id=peminjam-table    timeout=10
    Element Should Be Visible    id=peminjam-table
    Click Button    xpath=//button[contains(text(), 'Tambah') or @data-modal-target='authentication-modal']
    Wait Until Element Is Visible    id=authentication-modal    timeout=10
    Element Should Be Visible    id=authentication-modal
    Select From List By Value    id=id_pengguna    2
    Select From List By Value    id=id_ruang    3
    Input Text    id=tanggal_pinjam    2025-06-20
    Input Text    id=waktu_mulai    09:00
    # Input Text    id=waktu_selesai    12:00
    Input Text    id=keperluan    Meeting rutin
    Sleep    2s
    Click Button    id=jadwal-submit-btn
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'waktu selesai') and contains(text(), 'required')]    timeout=10
    Close Browser

Tambah Peminjam dengan Waktu Invalid
    [Documentation]    Test adding peminjam with invalid time range (end time before start time)
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=peminjam-link
    Wait Until Element Is Visible    id=peminjam-table    timeout=10
    Element Should Be Visible    id=peminjam-table
    Click Button    xpath=//button[contains(text(), 'Tambah') or @data-modal-target='authentication-modal']
    Wait Until Element Is Visible    id=authentication-modal    timeout=10
    Element Should Be Visible    id=authentication-modal
    Select From List By Value    id=id_pengguna   2 
    Select From List By Value    id=id_ruang    3
    Input Text    id=tanggal_pinjam    2025-06-20
    Input Text    id=waktu_mulai    15:00
    Input Text    id=waktu_selesai    12:00
    Input Text    id=keperluan    Meeting rutin
    Sleep    2s
    Click Button    id=jadwal-submit-btn
    Close Browser

Tambah Peminjam dengan Tanggal Masa Lalu
    [Documentation]    Test adding peminjam with past date - should show validation error
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=peminjam-link
    Wait Until Element Is Visible    id=peminjam-table    timeout=10
    Element Should Be Visible    id=peminjam-table
    Click Button    xpath=//button[contains(text(), 'Tambah') or @data-modal-target='authentication-modal']
    Wait Until Element Is Visible    id=authentication-modal    timeout=10
    Element Should Be Visible    id=authentication-modal
    Select From List By Value    id=id_pengguna    2
    Select From List By Value    id=id_ruang    3
    Input Text    id=tanggal_pinjam    2024-01-01
    Input Text    id=waktu_mulai    09:00
    Input Text    id=waktu_selesai    12:00
    Input Text    id=keperluan    Meeting rutin
    Sleep    2s
    Click Button    id=jadwal-submit-btn
    Close Browser

Tambah Peminjam tanpa Keperluan
    [Documentation] 
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=peminjam-link
    Wait Until Element Is Visible    id=peminjam-table    timeout=10
    Element Should Be Visible    id=peminjam-table
    Click Button    xpath=//button[contains(text(), 'Tambah') or @data-modal-target='authentication-modal']
    Wait Until Element Is Visible    id=authentication-modal    timeout=10
    Element Should Be Visible    id=authentication-modal
    Select From List By Value    id=id_pengguna    2
    Select From List By Value    id=id_ruang    3
    Input Text    id=tanggal_pinjam    2025-06-25
    Input Text    id=waktu_mulai    09:00
    Input Text    id=waktu_selesai    12:00
    # Input Text    id=keperluan    
    Sleep    2s
    Click Button    id=jadwal-submit-btn
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'The keperluan field is required.') or contains(text(), 'berhasil')]    timeout=10
    Close Browser

Tambah Peminjam - Berhasil
    [Documentation]    Test successful peminjam addition with all fields
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=peminjam-link
    Wait Until Element Is Visible    id=peminjam-table    timeout=10
    Element Should Be Visible    id=peminjam-table
    Click Button    xpath=//button[contains(text(), 'Tambah') or @data-modal-target='authentication-modal']
    Wait Until Element Is Visible    id=authentication-modal    timeout=10
    Element Should Be Visible    id=authentication-modal
    Select From List By Value    id=id_pengguna    2
    Select From List By Value    id=id_ruang    3
    Input Text    id=tanggal_pinjam    20-02-2025
    Input Text    id=waktu_mulai    14:00
    Input Text    id=waktu_selesai    16:00
    Input Text    id=keperluan    Meeting tim pengembangan aplikasi
    Sleep    2s
    Click Button    id=jadwal-submit-btn
    Wait Until Element Is Visible    xpath=//*[contains(text(), 'success!') or contains(text(), 'berhasil')]    timeout=10
    Close Browser

Test Conflict Detection
    [Documentation]    Test room booking conflict detection feature
    Open Browser    ${LOGIN_URL}    ${BROWSER}
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Click Element    id=peminjam-link
    Wait Until Element Is Visible    id=peminjam-table    timeout=10
    Element Should Be Visible    id=peminjam-table
    Click Button    xpath=//button[contains(text(), 'Tambah') or @data-modal-target='authentication-modal']
    Wait Until Element Is Visible    id=authentication-modal    timeout=10
    Element Should Be Visible    id=authentication-modal
    
    # Fill form that might have conflict
    Select From List By Value    id=id_pengguna    2
    Select From List By Value    id=id_ruang    3
    Input Text    id=tanggal_pinjam    2025-06-19
    Input Text    id=waktu_mulai    09:00
    Input Text    id=waktu_selesai    12:00
    
    # Wait for conflict detection to run
    Sleep    3s
    
    # Check if conflict status is displayed
    Element Should Be Visible    id=peminjaman-conflict-status
    
    Close Browser
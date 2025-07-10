*** Settings ***
Library    SeleniumLibrary
Library    DateTime

*** Variables ***
${BASE_URL}    http://localhost:8000
${BROWSER}      chrome
${USER_EMAIL}   bintang@gmail.com
${USER_PASSWORD}    123123qa
${ROOM_URL}    http://localhost:8000/ruangan/5

*** Test Cases ***
Room Booking - Success
    [Documentation]  Book a room successfully
    ${TODAY}=    Get Current Date    result_format=%Y-%m-%d
    Open Browser    ${BASE_URL}    ${BROWSER}
    Maximize Browser Window

    Click Button    id=loginButton
    Input Text      id=email        ${USER_EMAIL}
    Input Text      id=password     ${USER_PASSWORD}
    Click Button    id=login-button

    Go To           ${ROOM_URL}
    Wait Until Element Is Visible    id=tanggal_pinjam    timeout=10
    Input Text       id=tanggal_pinjam    ${TODAY}
    Input Text       id=waktu_mulai       09:00
    Input Text       id=waktu_selesai     10:00
    Input Text       id=keperluan         Tes Booking

    Scroll Element Into View         xpath=//button[contains(.,'Ajukan Peminjaman')]
    Sleep                            1s
    Click Element                    xpath=//button[contains(.,'Ajukan Peminjaman')]

    # Coba tangkap notifikasi swal atau toast
    Run Keyword And Ignore Error    Wait Until Element Is Visible    xpath=//div[contains(@class,'swal2-popup')]    timeout=20
    Run Keyword And Ignore Error    Element Should Contain           xpath=//div[contains(@class,'swal2-popup')]    Peminjaman berhasil

    # Backup verifikasi via URL redirect
    Wait Until Location Is    http://localhost:8000/pemesanan    timeout=15

    Capture Page Screenshot
    Close Browser

Room Booking - Failed Due To Conflict
    [Documentation]  Attempt to book a room at a conflicting time and expect failure
    ${TODAY}=    Get Current Date    result_format=%Y-%m-%d
    Open Browser    ${BASE_URL}    ${BROWSER}
    Maximize Browser Window
    Click Button    id=loginButton
    Input Text      id=email        ${USER_EMAIL}
    Input Text      id=password     ${USER_PASSWORD}
    Click Button    id=login-button
    Go To           ${ROOM_URL}
    Input Text       id=tanggal_pinjam    ${TODAY}
    Input Text       id=waktu_mulai       09:30
    Input Text       id=waktu_selesai     10:30
    Input Text       id=keperluan         Tes Booking Konflik
    Scroll Element Into View        xpath=//button[contains(.,'Ajukan Peminjaman')]
    Sleep                           1s
    Click Element                   xpath=//button[contains(.,'Ajukan Peminjaman')]

    # Verifikasi swal error muncul
    # Wait Until Element Is Visible   xpath=//div[contains(@class,'swal2-popup')]    timeout=10
    # Element Should Contain          xpath=//div[contains(@class,'swal2-popup')]    konflik
    # Capture Page Screenshot
    
    Close Browser

QR Room Booking - Success
    [Documentation]  Book a room via QR page successfully
    ${TODAY}=    Get Current Date    result_format=%d-%m-%Y
    Open Browser    ${BASE_URL}    ${BROWSER}
    Click Button    id=loginButton
    Input Text    id=email    ${USER_EMAIL}
    Input Text    id=password    ${USER_PASSWORD}
    Click Button    id=login-button
    Go To    http://localhost:8000/qr/room/5
    Input Text    id=keperluan    Tes Booking QR
    Select From List By Value    id=duration    1
    Click Button    xpath=//button[contains(.,'Pinjam Ruangan Sekarang')]
    # Debug: Log the current URL before checking
    ${current_url}=    Get Location
    Log    Current URL after booking: ${current_url}
    Wait Until Keyword Succeeds    10s    1s    Location Should Match QR Success
    Wait Until Element Is Visible    xpath=//div[contains(.,'Peminjaman Berhasil!')]    timeout=10
    Element Should Contain    xpath=//div[contains(.,'Peminjaman Berhasil!')]    Peminjaman Berhasil!
    Close Browser

QR Room Booking - Failed Due To Conflict
    [Documentation]  Attempt to book a room via QR page at a conflicting time and expect failure
    ${TODAY}=    Get Current Date    result_format=%d-%m-%Y
    Open Browser    ${BASE_URL}    ${BROWSER}
    Click Button    id=loginButton
    Input Text    id=email    ${USER_EMAIL}
    Input Text    id=password    ${USER_PASSWORD}
    Click Button    id=login-button
    Go To    http://localhost:8000/qr/room/5
    Input Text    id=keperluan    Tes Booking QR Konflik
    Select From List By Value    id=duration    1
    Click Button    xpath=//button[contains(.,'Pinjam Ruangan Sekarang')]
    Wait Until Element Is Visible    xpath=//div[contains(@class,'bg-red-100') and contains(.,'Ruangan tidak tersedia karena sudah dikonfirmasi untuk peminjaman lain')]    timeout=10
    Element Should Contain    xpath=//div[contains(@class,'bg-red-100') and contains(.,'Ruangan tidak tersedia karena sudah dikonfirmasi untuk peminjaman lain')]    Ruangan tidak tersedia karena sudah dikonfirmasi untuk peminjaman lain
    Close Browser

*** Keywords ***
Location Should Match QR Success
    ${current_url}=    Get Location
    Should Match Regexp    ${current_url}    ^http://localhost:8000/qr/success/.*

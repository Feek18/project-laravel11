*** Settings ***
Library    SeleniumLibrary

*** Variables ***
${BASE_URL}    http://localhost:8000
${PROFILE_URL}    http://localhost:8000/profile
${BROWSER}      chrome
${USER_EMAIL}   bintang@gmail.com
${USER_PASSWORD}    123123qa

*** Test Cases ***
Login - User Sukses
    [Documentation]  Login as user and expect redirect to profile page
    Open Browser    ${BASE_URL}    ${BROWSER}
    Click Button    id=loginButton
    Input Text    id=email    ${USER_EMAIL}
    Input Text    id=password    ${USER_PASSWORD}
    Click Button    id=login-button
    Wait Until Location Is    ${PROFILE_URL}    timeout=10
    Location Should Be    ${PROFILE_URL}
    Close Browser

*** Test Cases ***
Profile Update - User Sukses
    [Documentation]  Update user profile and expect success message

    Open Browser    ${BASE_URL}    ${BROWSER}
    Maximize Browser Window

    Click Button    id=loginButton
    Input Text    id=email    ${USER_EMAIL}
    Input Text    id=password    ${USER_PASSWORD}
    Click Button    id=login-button

    Wait Until Location Is    ${PROFILE_URL}    timeout=10
    Location Should Be        ${PROFILE_URL}

    # Wait for the form
    Wait Until Element Is Visible    id=nama    timeout=10
    Wait Until Element Is Enabled    id=nama    timeout=10
    Scroll Element Into View         id=nama
    Input Text    id=nama    Jack Moussent

    # Bypass readonly on no_telp
    Wait Until Element Is Visible    id=no_telp    timeout=10
    Execute JavaScript    document.getElementById('no_telp').removeAttribute('readonly')
    Scroll Element Into View         id=no_telp
    Input Text    id=no_telp    08123456789

    Wait Until Element Is Visible    id=alamat    timeout=10
    Scroll Element Into View         id=alamat
    Input Text    id=alamat    Jalan Baru No. 123

    Wait Until Element Is Visible    id=gender    timeout=10
    Scroll Element Into View         id=gender
    Select From List By Value        id=gender    pria

    # Upload dummy image
    Wait Until Element Is Visible    id=gambar    timeout=10
    Scroll Element Into View         id=gambar
    Choose File                      id=gambar    ${CURDIR}/dummy.jpg

    # Submit form
    Wait Until Element Is Visible    xpath=//button[contains(.,'Save') or contains(.,'Simpan')]    timeout=10
    Scroll Element Into View         xpath=//button[contains(.,'Save') or contains(.,'Simpan')]
    Click Button                     xpath=//button[contains(.,'Save') or contains(.,'Simpan')]

    # Wait for toast
    Wait Until Element Is Visible    xpath=//div[.//strong[contains(translate(text(),'ABCDEFGHIJKLMNOPQRSTUVWXYZ','abcdefghijklmnopqrstuvwxyz'),'success!')] and .//span[contains(text(),'Profile berhasil diupdate')]]    timeout=10
    Element Should Contain           xpath=//div[.//strong[contains(translate(text(),'ABCDEFGHIJKLMNOPQRSTUVWXYZ','abcdefghijklmnopqrstuvwxyz'),'success!')] and .//span[contains(text(),'Profile berhasil diupdate')]]    Profile berhasil diupdate

    # Handle modal OK
    Wait Until Element Is Visible    xpath=//button[contains(.,'OK')]    timeout=10
    Scroll Element Into View         xpath=//button[contains(.,'OK')]
    Click Button                     xpath=//button[contains(.,'OK')]

    Capture Page Screenshot
    Close Browser

*** Test Cases ***
Profile Update - Validation Required Fields
    [Documentation]  Attempt to update profile with empty required fields and expect validation errors

    Open Browser    ${BASE_URL}    ${BROWSER}
    Maximize Browser Window

    Click Button    id=loginButton
    Input Text      id=email       ${USER_EMAIL}
    Input Text      id=password    ${USER_PASSWORD}
    Click Button    id=login-button

    Wait Until Location Is    ${PROFILE_URL}    timeout=10
    Location Should Be        ${PROFILE_URL}

    # 🛑 Pastikan modal SweetAlert2 sudah hilang
    Wait Until Keyword Succeeds    10s    1s    Element Should Not Be Visible    xpath=//div[contains(@class,'swal2-container')]

    # Clear NAMA
    Wait Until Element Is Visible    id=nama    timeout=10
    Scroll Element Into View         id=nama
    Click Element                    id=nama
    Clear Element Text               id=nama

    # Clear NO TELP - bypass readonly
    Wait Until Element Is Visible    id=no_telp    timeout=10
    Execute JavaScript               document.getElementById('no_telp').removeAttribute('readonly')
    Scroll Element Into View         id=no_telp
    Click Element                    id=no_telp
    Clear Element Text               id=no_telp

    # Clear ALAMAT
    Wait Until Element Is Visible    id=alamat    timeout=10
    Scroll Element Into View         id=alamat
    Click Element                    id=alamat
    Clear Element Text               id=alamat

    # Click Save button
    Wait Until Element Is Visible    xpath=//button[contains(.,'Save') or contains(.,'Simpan')]    timeout=10
    Scroll Element Into View         xpath=//button[contains(.,'Save') or contains(.,'Simpan')]
    Click Button                     xpath=//button[contains(.,'Save') or contains(.,'Simpan')]

    # Wait for validation messages
    Wait Until Page Contains         required    timeout=10
    Wait Until Page Contains         nama        timeout=5
    Wait Until Page Contains         telp        timeout=5
    Wait Until Page Contains         alamat      timeout=5

    Capture Page Screenshot
    Close Browser

Profile Update - Validation Gambar Required
    [Documentation]  Attempt to update profile with no image uploaded and expect validation error

    Open Browser    ${BASE_URL}    ${BROWSER}
    Maximize Browser Window

    Click Button    id=loginButton
    Input Text      id=email       ${USER_EMAIL}
    Input Text      id=password    ${USER_PASSWORD}
    Click Button    id=login-button

    Wait Until Location Is    ${PROFILE_URL}    timeout=10
    Location Should Be        ${PROFILE_URL}

    # Tutup swal2 jika masih muncul
    Run Keyword And Ignore Error    Wait Until Element Is Visible    xpath=//button[contains(.,'OK')]    timeout=5
    Run Keyword And Ignore Error    Click Button    xpath=//button[contains(.,'OK')]

    # Isi semua field kecuali gambar
    Wait Until Element Is Visible    id=nama    timeout=10
    Input Text    id=nama    New User Name

    Wait Until Element Is Visible    id=no_telp    timeout=10
    Execute JavaScript               document.getElementById('no_telp').removeAttribute('readonly')
    Input Text    id=no_telp    08123456789

    Wait Until Element Is Visible    id=alamat    timeout=10
    Input Text    id=alamat    Jalan Baru No. 123

    Select From List By Value    id=gender    pria

    # Jangan isi gambar

    # Klik simpan
    Wait Until Element Is Visible    xpath=//button[contains(.,'Save') or contains(.,'Simpan')]    timeout=10
    Scroll Element Into View         xpath=//button[contains(.,'Save') or contains(.,'Simpan')]
    Click Button                     xpath=//button[contains(.,'Save') or contains(.,'Simpan')]

    # Cek pesan validasi
    Wait Until Page Contains         gambar     timeout=10
    Wait Until Page Contains         required   timeout=10

    Capture Page Screenshot
    Close Browser

Profile Update - Validation Nama Required
    [Documentation]  Attempt to update profile with empty 'Nama' and expect validation error

    Open Browser    ${BASE_URL}    ${BROWSER}
    Maximize Browser Window

    Click Button    id=loginButton
    Input Text      id=email       ${USER_EMAIL}
    Input Text      id=password    ${USER_PASSWORD}
    Click Button    id=login-button

    Wait Until Location Is          ${PROFILE_URL}    timeout=10
    Location Should Be              ${PROFILE_URL}

    # Tutup modal swal jika ada
    Run Keyword And Ignore Error    Wait Until Element Is Visible    xpath=//button[contains(.,'OK')]    timeout=5
    Run Keyword And Ignore Error    Click Button                     xpath=//button[contains(.,'OK')]

    # Upload dummy image
    Wait Until Element Is Visible    id=gambar    timeout=10
    Choose File                      id=gambar    ${CURDIR}/dummy.jpg

    # Isi semua field kecuali nama
    Wait Until Element Is Visible    id=no_telp    timeout=10
    Execute JavaScript               document.getElementById('no_telp').removeAttribute('readonly')
    Input Text                       id=no_telp    08123456789

    Input Text                       id=email     bintang@gmail.com
    Input Text                       id=alamat    Jalan Baru No. 123
    Select From List By Value        id=gender    pria

    # Kosongkan nama
    Wait Until Element Is Visible    id=nama    timeout=10
    Scroll Element Into View         id=nama
    Click Element                    id=nama
    Clear Element Text               id=nama

    # Klik tombol simpan
    Wait Until Element Is Visible    xpath=//button[contains(.,'Save') or contains(.,'Simpan')]    timeout=10
    Scroll Element Into View         xpath=//button[contains(.,'Save') or contains(.,'Simpan')]
    Click Button                     xpath=//button[contains(.,'Save') or contains(.,'Simpan')]

    # Cek pesan validasi
    Wait Until Page Contains         nama         timeout=5
    Wait Until Page Contains         required     timeout=10

    Capture Page Screenshot
    Close Browser

Profile Update - Validation Email Required
    [Documentation]  Attempt to update profile with empty 'Email' and expect validation error

    Open Browser    ${BASE_URL}    ${BROWSER}
    Maximize Browser Window

    Click Button    id=loginButton
    Input Text      id=email       ${USER_EMAIL}
    Input Text      id=password    ${USER_PASSWORD}
    Click Button    id=login-button

    Wait Until Location Is          ${PROFILE_URL}    timeout=10
    Location Should Be              ${PROFILE_URL}

    # Tutup modal swal jika muncul
    Run Keyword And Ignore Error    Wait Until Element Is Visible    xpath=//button[contains(.,'OK')]    timeout=5
    Run Keyword And Ignore Error    Click Button                     xpath=//button[contains(.,'OK')]

    # Upload dummy image
    Wait Until Element Is Visible    id=gambar    timeout=10
    Choose File                      id=gambar    ${CURDIR}/dummy.jpg

    # Isi semua field kecuali email
    Wait Until Element Is Visible    id=nama    timeout=10
    Input Text                       id=nama     New User Name

    Wait Until Element Is Visible    id=no_telp    timeout=10
    Execute JavaScript               document.getElementById('no_telp').removeAttribute('readonly')
    Input Text                       id=no_telp    08123456789

    Input Text                       id=alamat    Jalan Baru No. 123
    Select From List By Value        id=gender    pria

    # Kosongkan email
    Wait Until Element Is Visible    id=email    timeout=10
    Scroll Element Into View         id=email
    Click Element                    id=email
    Clear Element Text               id=email

    # Klik tombol simpan
    Wait Until Element Is Visible    xpath=//button[contains(.,'Save') or contains(.,'Simpan')]    timeout=10
    Scroll Element Into View         xpath=//button[contains(.,'Save') or contains(.,'Simpan')]
    Click Button                     xpath=//button[contains(.,'Save') or contains(.,'Simpan')]

    # Cek pesan validasi
    Wait Until Page Contains         email        timeout=5
    Wait Until Page Contains         required     timeout=10

    Capture Page Screenshot
    Close Browser

Profile Update - Validation No Telp Required
    [Documentation]  Attempt to update profile with empty 'No. Telp' and expect validation error
    Open Browser    ${BASE_URL}    ${BROWSER}
    Maximize Browser Window

    Click Button    id=loginButton
    Input Text      id=email       ${USER_EMAIL}
    Input Text      id=password    ${USER_PASSWORD}
    Click Button    id=login-button

    Wait Until Location Is          ${PROFILE_URL}    timeout=10
    Location Should Be              ${PROFILE_URL}
    Run Keyword And Ignore Error    Click Button    xpath=//button[contains(.,'OK')]

    Wait Until Element Is Visible    id=no_telp    timeout=10
    Execute JavaScript               document.getElementById('no_telp').removeAttribute('readonly')
    Scroll Element Into View         id=no_telp
    Click Element                    id=no_telp
    Clear Element Text               id=no_telp

    Choose File                      id=gambar     ${CURDIR}/dummy.jpg
    Input Text                       id=nama       New User Name
    Input Text                       id=email      bintang@gmail.com
    Input Text                       id=alamat     Jalan Baru No. 123
    Select From List By Value        id=gender     pria

    Click Button                     xpath=//button[contains(.,'Save') or contains(.,'Simpan')]

    Wait Until Page Contains         telp      timeout=5
    Wait Until Page Contains         required  timeout=10
    Capture Page Screenshot
    Close Browser

Profile Update - Validation Alamat Required
    [Documentation]  Attempt to update profile with empty 'Alamat' and expect validation error
    Open Browser    ${BASE_URL}    ${BROWSER}
    Maximize Browser Window

    Click Button    id=loginButton
    Input Text      id=email       ${USER_EMAIL}
    Input Text      id=password    ${USER_PASSWORD}
    Click Button    id=login-button

    Wait Until Location Is          ${PROFILE_URL}    timeout=10
    Location Should Be              ${PROFILE_URL}
    Run Keyword And Ignore Error    Click Button    xpath=//button[contains(.,'OK')]

    Wait Until Element Is Visible    id=alamat    timeout=10
    Scroll Element Into View         id=alamat
    Click Element                    id=alamat
    Clear Element Text               id=alamat

    Choose File                      id=gambar     ${CURDIR}/dummy.jpg
    Input Text                       id=nama       New User Name
    Input Text                       id=email      bintang@gmail.com
    Execute JavaScript               document.getElementById('no_telp').removeAttribute('readonly')
    Input Text                       id=no_telp    08123456789
    Select From List By Value        id=gender     pria

    Click Button                     xpath=//button[contains(.,'Save') or contains(.,'Simpan')]

    Wait Until Page Contains         alamat     timeout=5
    Wait Until Page Contains         required   timeout=10
    Capture Page Screenshot
    Close Browser

Profile Update - Validation Gender Required
    [Documentation]  Attempt to update profile with empty 'Gender' and expect validation error
    Open Browser    ${BASE_URL}    ${BROWSER}
    Maximize Browser Window

    Click Button    id=loginButton
    Input Text      id=email       ${USER_EMAIL}
    Input Text      id=password    ${USER_PASSWORD}
    Click Button    id=login-button

    Wait Until Location Is          ${PROFILE_URL}    timeout=10
    Location Should Be              ${PROFILE_URL}
    Run Keyword And Ignore Error    Click Button    xpath=//button[contains(.,'OK')]

    Choose File                      id=gambar     ${CURDIR}/dummy.jpg
    Input Text                       id=nama       New User Name
    Input Text                       id=email      bintang@gmail.com
    Execute JavaScript               document.getElementById('no_telp').removeAttribute('readonly')
    Input Text                       id=no_telp    08123456789
    Input Text                       id=alamat     Jalan Baru No. 123

    Execute JavaScript               document.getElementById('gender').selectedIndex = -1;

    Click Button                     xpath=//button[contains(.,'Save') or contains(.,'Simpan')]

    Wait Until Page Contains         gender     timeout=5
    Wait Until Page Contains         required   timeout=10
    Capture Page Screenshot
    Close Browser

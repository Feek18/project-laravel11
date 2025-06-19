*** Settings ***
Library    SeleniumLibrary

*** Variables ***
${BASE_URL}    http://localhost:8000
${PROFILE_URL}    http://localhost:8000/profile
${BROWSER}      chrome
${USER_EMAIL}   user@gmail.com
${USER_PASSWORD}    asdasd

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

Profile Update - User Sukses
    [Documentation]  Update user profile and expect success message
    Open Browser    ${BASE_URL}    ${BROWSER}
    Click Button    id=loginButton
    Input Text    id=email    ${USER_EMAIL}
    Input Text    id=password    ${USER_PASSWORD}
    Click Button    id=login-button
    Wait Until Location Is    ${PROFILE_URL}    timeout=10
    Location Should Be    ${PROFILE_URL}
    # The profile form is already on the profile page
    Wait Until Element Is Visible    id=nama    timeout=10
    Input Text    id=nama    New User Name
    Wait Until Element Is Visible    id=no_telp    timeout=10
    Execute JavaScript    document.getElementById('no_telp').removeAttribute('readonly');
    Input Text    id=no_telp    08123456789
    Input Text    id=alamat    Jalan Baru No. 123
    Select From List By Value    id=gender    pria
    # Upload dummy picture
    Choose File    id=gambar    ${CURDIR}/dummy.jpg
    Click Button    xpath=//button[contains(.,'Save') or contains(.,'Simpan')]
    # Try a more flexible xpath for the toast notification
    Wait Until Element Is Visible    xpath=//div[.//strong[contains(translate(text(),'ABCDEFGHIJKLMNOPQRSTUVWXYZ','abcdefghijklmnopqrstuvwxyz'),'success!')] and .//span[contains(text(),'Profile berhasil diupdate')]]    timeout=10
    Element Should Contain    xpath=//div[.//strong[contains(translate(text(),'ABCDEFGHIJKLMNOPQRSTUVWXYZ','abcdefghijklmnopqrstuvwxyz'),'success!')] and .//span[contains(text(),'Profile berhasil diupdate')]]    Profile berhasil diupdate
    # Wait for the modal and click OK
    Wait Until Element Is Visible    xpath=//button[contains(.,'OK')]    timeout=10
    Click Button    xpath=//button[contains(.,'OK')]
    Close Browser

Profile Update - Validation Required Fields
    [Documentation]  Attempt to update profile with empty required fields and expect validation errors
    Open Browser    ${BASE_URL}    ${BROWSER}
    Click Button    id=loginButton
    Input Text    id=email    ${USER_EMAIL}
    Input Text    id=password    ${USER_PASSWORD}
    Click Button    id=login-button
    Wait Until Location Is    ${PROFILE_URL}    timeout=10
    Location Should Be    ${PROFILE_URL}
    # Clear required fields
    Clear Element Text    id=nama
    # Remove readonly if present before clearing no_telp
    Wait Until Element Is Visible    id=no_telp    timeout=10
    Clear Element Text    id=no_telp
    Clear Element Text    id=alamat
    # Try to save with missing required fields
    Click Button    xpath=//button[contains(.,'Save') or contains(.,'Simpan')]
    # Check for validation error messages
    Wait Until Page Contains    Nama
    Wait Until Page Contains    No. Telp
    Wait Until Page Contains    Alamat
    # Wait for any error message containing 'field is required'
    Wait Until Page Contains    field is required    timeout=10
    Close Browser

Profile Update - Validation Gambar Required
    [Documentation]  Attempt to update profile with no image uploaded and expect validation error
    Open Browser    ${BASE_URL}    ${BROWSER}
    Click Button    id=loginButton
    Input Text    id=email    ${USER_EMAIL}
    Input Text    id=password    ${USER_PASSWORD}
    Click Button    id=login-button
    Wait Until Location Is    ${PROFILE_URL}    timeout=10
    Location Should Be    ${PROFILE_URL}
    # Fill all fields except gambar
    Input Text    id=nama    New User Name
    Input Text    id=email    user@gmail.com
    Wait Until Element Is Visible    id=no_telp    timeout=10
    Input Text    id=no_telp    08123456789
    Input Text    id=alamat    Jalan Baru No. 123
    Select From List By Value    id=gender    pria
    # Do not upload image
    Click Button    xpath=//button[contains(.,'Save') or contains(.,'Simpan')]
    Wait Until Page Contains    field is required    timeout=10
    Close Browser

Profile Update - Validation Nama Required
    [Documentation]  Attempt to update profile with empty 'Nama' and expect validation error
    Open Browser    ${BASE_URL}    ${BROWSER}
    Click Button    id=loginButton
    Input Text    id=email    ${USER_EMAIL}
    Input Text    id=password    ${USER_PASSWORD}
    Click Button    id=login-button
    Wait Until Location Is    ${PROFILE_URL}    timeout=10
    Location Should Be    ${PROFILE_URL}
    # Fill all fields except nama
    # Upload dummy image
    Wait Until Element Is Visible    id=no_telp    timeout=10
    Choose File    id=gambar    ${CURDIR}/dummy.jpg
    Input Text    id=email    user@gmail.com
    Input Text    id=no_telp    08123456789
    Input Text    id=alamat    Jalan Baru No. 123
    Select From List By Value    id=gender    pria
    Clear Element Text    id=nama
    Click Button    xpath=//button[contains(.,'Save') or contains(.,'Simpan')]
    Wait Until Page Contains    field is required    timeout=10
    Close Browser

Profile Update - Validation Email Required
    [Documentation]  Attempt to update profile with empty 'Email' and expect validation error
    Open Browser    ${BASE_URL}    ${BROWSER}
    Click Button    id=loginButton
    Input Text    id=email    ${USER_EMAIL}
    Input Text    id=password    ${USER_PASSWORD}
    Click Button    id=login-button
    Wait Until Location Is    ${PROFILE_URL}    timeout=10
    Location Should Be    ${PROFILE_URL}
    # Fill all fields except email
    Wait Until Element Is Visible    id=no_telp    timeout=10
    Choose File    id=gambar    ${CURDIR}/dummy.jpg
    Input Text    id=nama    New User Name
    Input Text    id=no_telp    08123456789
    Input Text    id=alamat    Jalan Baru No. 123
    Select From List By Value    id=gender    pria
    Clear Element Text    id=email
    Click Button    xpath=//button[contains(.,'Save') or contains(.,'Simpan')]
    Wait Until Page Contains    field is required    timeout=10
    Close Browser

Profile Update - Validation No Telp Required
    [Documentation]  Attempt to update profile with empty 'No. Telp' and expect validation error
    Open Browser    ${BASE_URL}    ${BROWSER}
    Click Button    id=loginButton
    Input Text    id=email    ${USER_EMAIL}
    Input Text    id=password    ${USER_PASSWORD}
    Click Button    id=login-button
    Wait Until Location Is    ${PROFILE_URL}    timeout=10
    Location Should Be    ${PROFILE_URL}
    # Fill all fields except no_telp
    Wait Until Element Is Visible    id=no_telp    timeout=10
    Choose File    id=gambar    ${CURDIR}/dummy.jpg
    Input Text    id=nama    New User Name
    Input Text    id=email    user@gmail.com
    Input Text    id=alamat    Jalan Baru No. 123
    Select From List By Value    id=gender    pria
    Clear Element Text    id=no_telp
    Click Button    xpath=//button[contains(.,'Save') or contains(.,'Simpan')]
    Wait Until Page Contains    field is required    timeout=10
    Close Browser

Profile Update - Validation Alamat Required
    [Documentation]  Attempt to update profile with empty 'Alamat' and expect validation error
    Open Browser    ${BASE_URL}    ${BROWSER}
    Click Button    id=loginButton
    Input Text    id=email    ${USER_EMAIL}
    Input Text    id=password    ${USER_PASSWORD}
    Click Button    id=login-button
    Wait Until Location Is    ${PROFILE_URL}    timeout=10
    Location Should Be    ${PROFILE_URL}
    # Fill all fields except alamat
    Wait Until Element Is Visible    id=no_telp    timeout=10
    Choose File    id=gambar    ${CURDIR}/dummy.jpg
    Input Text    id=nama    New User Name
    Input Text    id=email    user@gmail.com
    Input Text    id=no_telp    08123456789
    Clear Element Text    id=alamat
    Select From List By Value    id=gender    pria
    Click Button    xpath=//button[contains(.,'Save') or contains(.,'Simpan')]
    Wait Until Page Contains    field is required    timeout=10
    Close Browser

Profile Update - Validation Gender Required
    [Documentation]  Attempt to update profile with empty 'Gender' and expect validation error
    Open Browser    ${BASE_URL}    ${BROWSER}
    Click Button    id=loginButton
    Input Text    id=email    ${USER_EMAIL}
    Input Text    id=password    ${USER_PASSWORD}
    Click Button    id=login-button
    Wait Until Location Is    ${PROFILE_URL}    timeout=10
    Location Should Be    ${PROFILE_URL}
    # Fill all fields except gender
    Wait Until Element Is Visible    id=no_telp    timeout=10
    Choose File    id=gambar    ${CURDIR}/dummy.jpg
    Input Text    id=nama    New User Name
    Input Text    id=email    user@gmail.com
    Input Text    id=no_telp    08123456789
    Input Text    id=alamat    Jalan Baru No. 123
    # Deselect gender (set to empty)
    Execute JavaScript    document.getElementById('gender').selectedIndex = -1;
    Click Button    xpath=//button[contains(.,'Save') or contains(.,'Simpan')]
    Wait Until Page Contains    field is required    timeout=10
    Close Browser

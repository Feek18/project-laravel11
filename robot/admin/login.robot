*** Settings ***
Library    SeleniumLibrary

*** Variables ***
${BASE_URL}    http://localhost:8000
${LOGIN_URL}    http://localhost:8000/login
${BROWSER}      chrome
${EMAIL}     admin@gmail.com
${PASSWORD}     admin123

*** Test Cases ***
Login - Page
    [Documentation] 
    Open Browser    ${BASE_URL}    ${BROWSER}
    Click Button    id=loginButton
    Wait Until Element Is Visible    id=login-button    timeout=10
    Element Should Be Visible    id=login-button
    Close Browser

Login - Admin Dengan Email Kosong
    [Documentation] 
    Open Browser    ${BASE_URL}    ${BROWSER}
    Click Button    id=loginButton
    Input Text    id=email    ${EMPTY}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Sleep    2s
    Location Should Be    ${LOGIN_URL}
    Close Browser

Login - Admin Dengan Email Salah
    [Documentation] 
    Open Browser    ${BASE_URL}    ${BROWSER}
    Click Button    id=loginButton
    Input Text    id=email    invalidemail@gmail.com
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=error    timeout=10
    Element Should Be Visible    id=error
    Close Browser

Login - Admin Dengan Password Kosong
    [Documentation] 
    Open Browser    ${BASE_URL}    ${BROWSER}
    Click Button    id=loginButton
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${EMPTY}
    Click Button    id=login-button
    Sleep    2s
    Location Should Be    ${LOGIN_URL}
    Close Browser

Login - Admin Dengan Password Salah
    [Documentation] 
    Open Browser    ${BASE_URL}    ${BROWSER}
    Click Button    id=loginButton
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    invalid_password
    Click Button    id=login-button
    Wait Until Element Is Visible    id=error    timeout=10
    Element Should Be Visible    id=error
    Close Browser

Login - Admin Dengan Email dan Password Salah
    [Documentation] 
    Open Browser    ${BASE_URL}    ${BROWSER}
    Click Button    id=loginButton
    Input Text    id=email    invalidemail@gmail.com
    Input Text    id=password    invalid_password
    Click Button    id=login-button
    Wait Until Element Is Visible    id=error    timeout=10
    Element Should Be Visible    id=error
    Close Browser

Login - Admin Sukses
    [Documentation] 
    Open Browser    ${BASE_URL}    ${BROWSER}
    Click Button    id=loginButton
    Input Text    id=email    ${EMAIL}
    Input Text    id=password    ${PASSWORD}
    Click Button    id=login-button
    Wait Until Element Is Visible    id=dashboard    timeout=10
    Element Should Be Visible    id=dashboard
    Close Browser

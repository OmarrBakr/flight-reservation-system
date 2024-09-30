function showAdditionalInfo() {
    const accountType = document.getElementById('signupType').value;

    if (accountType === 'company') {
        window.open('company_signup.html', '_blank');
    } else if (accountType === 'passenger') {
        window.open('passenger_signup.html', '_blank');
    }
}

function updateForm() {
    const accountType = document.getElementById('signupType').value;
    const companyFields = document.getElementById('companyFields');
    const passengerFields = document.getElementById('passengerFields');

    if (accountType === 'company') {
        companyFields.style.display = 'block';
        passengerFields.style.display = 'none';
    } else if (accountType === 'passenger') {
        companyFields.style.display = 'none';
        passengerFields.style.display = 'block';
    } else {
        companyFields.style.display = 'none';
        passengerFields.style.display = 'none';
    }
}

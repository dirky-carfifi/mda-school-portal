// Navbar Menu Handling
const navbarMenu = document.getElementById("menu");
const burgerMenu = document.getElementById("burger");
const overlayMenu = document.querySelector(".overlay");

// Open/Close Navbar Menu on Click Burger
if (burgerMenu && navbarMenu) {
    burgerMenu.addEventListener("click", () => {
        burgerMenu.classList.toggle("is-active");
        navbarMenu.classList.toggle("is-active");
    });
}

// Close Navbar Menu on Click Links
document.querySelectorAll(".menu-link").forEach((link) => {
    link.addEventListener("click", () => {
        burgerMenu.classList.remove("is-active");
        navbarMenu.classList.remove("is-active");
    });
});

// Fixed Navbar Menu on Window Resize
window.addEventListener("resize", () => {
    if (window.innerWidth >= 992) {
        if (navbarMenu.classList.contains("is-active")) {
            navbarMenu.classList.remove("is-active");
            overlayMenu.classList.remove("is-active");
        }
    }
});

document.addEventListener('DOMContentLoaded', () => {
    loadParticipants();
});

document.getElementById('registrationForm').addEventListener('submit', (e) => {
    e.preventDefault(); 

    
    const fullName = document.getElementById('fullName').value.trim();
    const section = document.getElementById('section').value.trim();

   
    if (!fullName || !section) {
        alert("Please fill in all fields.");
        return;
    }

    
    const participant = {
        fullName,
        section,
    };

   
    saveParticipant(participant);

     document.getElementById('registrationForm').reset();

    
    loadParticipants();
});


function saveParticipant(participant) {
    let participants = JSON.parse(localStorage.getItem('participants')) || [];
    participants.push(participant);
    localStorage.setItem('participants', JSON.stringify(participants));
}


function loadParticipants() {
    const participants = JSON.parse(localStorage.getItem('participants')) || [];
    const participantsList = document.getElementById('participantsList');

    participantsList.innerHTML = '';

    
    participants.forEach((participant, index) => {
        const participantDiv = document.createElement('div');
        participantDiv.className = 'participant';
        participantDiv.innerHTML = `
            <strong>${index + 1}. ${participant.fullName}</strong><br>
            Section: ${participant.section}
        `;
        participantsList.appendChild(participantDiv);
    });
}


function compressAndPrint() {
    const participants = JSON.parse(localStorage.getItem('participants')) || [];

    if (participants.length === 0) {
        alert("No participants to print.");
        return;
    }

    let compressedData = participants.map((participant, index) => {
        return <div class="participant-entry">${index + 1}. ${participant.fullName} - ${participant.section}</div>;
    }).join('');


    const printContent = `
        <div style="font-family: Arial, sans-serif; margin: 20px;">
            <h1 style="text-align: center;">Registered Participants</h1>
            <div style="column-count: 2; column-gap: 40px;">
                ${compressedData}
            </div>
        </div>
    `;

    
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>Print Participants</title>
                <style>
                    .participant-entry {
                        margin-bottom: 10px;
                        break-inside: avoid; /* Prevent entries from breaking across columns */
                    }
                    @media print {
                        body {
                            margin: 0;
                            padding: 0;
                        }
                        h1 {
                            font-size: 24px;
                            margin-bottom: 20px;
                        }
                        a {
                            text-decoration: none; /* Remove underline from links */
                            color: inherit; /* Use default text color */
                            pointer-events: none; /* Disable click events */
                        }
                    }
                </style>
            </head>
            <body>
                ${printContent}
            </body>
        </html>
    `);
    printWindow.document.close();

    printWindow.print();
}

// Dark and Light Mode Toggle
/*document.addEventListener("DOMContentLoaded", () => {
    const darkSwitch = document.getElementById("switch");

    darkSwitch.addEventListener("click", () => {
        document.documentElement.classList.toggle("darkmode");
        document.body.classList.toggle("darkmode");
    });
});*/

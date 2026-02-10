// contact shape: { id, first_name, last_name, phone_number, email }
const urlBase = "backend/routes/Contacts";
const extension = "php";

let contacts = []; // array of contact objects
let editingId = null; // null = add, number = edit

const contactsBody = document.getElementById("contactsBody");
const modal = document.getElementById("contactModal");
const form = document.getElementById("contactForm");

const addBtn = document.getElementById("addContactBtn");
const cancelBtn = document.getElementById("cancelBtn");
const searchBtn = document.getElementById("searchBtn");
const logoutBtn = document.getElementById("logoutBtn");

const searchInput = document.getElementById("searchInput");
const firstName = document.getElementById("firstName");
const lastName = document.getElementById("lastName");
const phoneNumber = document.getElementById("phoneNumber");
const email = document.getElementById("email");

//backend call functions - loadContacts, saveContact, deleteContact - pending implementation

function loadContacts() {

}

function saveContact(payload) {

}

function deleteContact(id) {

}

//contact form functions - open add, open edit, close modal, submit form (add or edit), search contacts

//open add function
function openAdd() {
  editingId = null;
  form.reset();

  const title = document.getElementById("formTitle");
  if (title) title.textContent = "Add Contact";

  modal.classList.remove("hidden");

}

//open edit function
function openEdit(id) {
  const c = contacts.find((x) => String(x.id) === String(id));
  if (!c) return;
  editingId = c.id;

  //fill inputs from contact object
  firstName.value = c.first_name ?? "";
  lastName.value = c.last_name ?? "";
  phoneNumber.value = c.phone_number ?? "";
  email.value = c.email ?? "";

  const title = document.getElementById("formTitle");
  if (title) title.textContent = "Edit Contact";

  modal.classList.remove("hidden");

}

//close modal function
function closeModal() {
  modal.classList.add("hidden");
  editingId = null;
  form.reset();

  const title = document.getElementById("formTitle");
  if (title) title.textContent = "Add Contact";
}

//contact rendering function
function renderContacts() {
  //clear the table body
  contactsBody.innerHTML = "";

  //go through every contact
  contacts.forEach(contact => {
    const row = document.createElement("tr");

    row.innerHTML = `
      <td>${contact.first_name}</td>
      <td>${contact.last_name}</td>
      <td>${contact.phone_number}</td>
      <td>${contact.email}</td>
      <td>
        <button class="editBtn" data-id="${contact.id}">Edit</button>
        <button class="deleteBtn" data-id="${contact.id}">Delete</button>
      </td>
    `;

    contactsBody.appendChild(row);
  });

  attachRowEvents();
}

//function for attaching edit and delete button functionality
function attachRowEvents() {

  //for edit
  document.querySelectorAll(".editBtn").forEach(btn => {
    btn.onclick = () => {
      const id = Number(btn.dataset.id);
      openEdit(id);
    };
  });

  //for delete
  document.querySelectorAll(".deleteBtn").forEach(btn => {
    btn.onclick = () => {
      const id = Number(btn.dataset.id);
      //pending backend call implementation  - replace the following two lines with deleteContact(id), as current implementation is just for frontend testing
      contacts = contacts.filter(c => c.id !== id);
      renderContacts();
    };
  });

}

//form submission functionality: when form is submitted, gather data and save contact (add or edit)
form.onsubmit = (e) => {
  e.preventDefault();

  const payload = {
    first_name: firstName.value,
    last_name: lastName.value,
    phone_number: phoneNumber.value,
    email: email.value,
  };

  if (!payload.first_name || !payload.last_name || !payload.phone_number || !payload.email) {
    alert("Please fill out all fields.");
    return;
  }
  if(saveContact(payload)){ // pending backend call implementation, remove from conditional once saveContact is working
    saveContact(payload);
  }
  closeModal(); // close modal after saving contact for now, pending backend functionality (delete once saveContact working)
};

//cancel button functionality
cancelBtn.onclick = () => {
  closeModal();
};

//add button functionality
addBtn.onclick = () => {
  openAdd();
};

//search button functionality
searchBtn.onclick = () => {
  const query = searchInput.value.toLowerCase();

  if (!query) {
    renderContacts();
    return;
  }

  const filtered = contacts.filter((c) =>
    (c.first_name ?? "").toLowerCase().includes(query) ||
    (c.last_name ?? "").toLowerCase().includes(query)
  );

  contactsBody.innerHTML = "";

  filtered.forEach((c) => {
    const row = document.createElement("tr");
    row.innerHTML = `
      <td>${c.first_name ?? ""}</td>
      <td>${c.last_name ?? ""}</td>
      <td>${c.phone_number ?? ""}</td>
      <td>${c.email ?? ""}</td>
      <td><button onclick="openEdit(${c.id})">Edit</button></td>
      <td><button onclick="deleteContact(${c.id})">Delete</button></td>
    `;
    contactsBody.appendChild(row);
  });

};

if (logoutBtn) { //conditional placeholder for logout when login/auth is implemented
  logoutBtn.onclick = () => {
    // clear user data and redirect to login page (index.html) - in progress
  };
}

//init function - load contacts waiting on backend call implementation, for now just renders empty table, remove if statement once loadContacts is working
if(loadContacts()){
  loadContacts();
}

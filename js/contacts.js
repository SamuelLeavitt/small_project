// contact shape: { id, first_name, last_name, phone_number, email }

let contacts = []; // array of contact objects
let editingId = null; // null = add, number = edit

const contactsBody = document.getElementById("contactsBody");
const modal = document.getElementById("contactModal");
const form = document.getElementById("contactForm");
const addBtn = document.getElementById("addContactBtn");
const cancelBtn = document.getElementById("cancelBtn");
const searchBtn = document.getElementById("searchBtn");
const firstName = document.getElementById("firstName");
const lastName = document.getElementById("lastName");
const phoneNumber = document.getElementById("phoneNumber");
const email = document.getElementById("email");

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
  const c = contacts.find(x => x.id === id);
  if (!c) return;
  editingId = id;

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

      contacts = contacts.filter(c => c.id !== id);
      renderContacts();
    };
  });

}

//form submission functionality: when form is submitted, gather data and save contact (add or edit) - in progress 
form.onsubmit = async (e) => {

};

//cancel button functionality
cancelBtn.onclick = () => {
  closeModal();
};

//add button functionality
addBtn.onclick = () => {
  openAdd();
};

//search button functionality - in progress
searchBtn.onclick = () => {

};

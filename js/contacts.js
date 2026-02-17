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

//backend call functions

function loadContacts() {
  const xhr = new XMLHttpRequest();
  const url = `${urlBase}/GetAll.${extension}`;

  xhr.open("POST", url, true);
  xhr.withCredentials = true;
  xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
  xhr.onreadystatechange = function () {
    if (xhr.readyState !== 4) return;

    if (xhr.status !== 200) {
      console.error("[GetAll] HTTP", xhr.status);
      console.error(xhr.responseText);
      return;
    }

    let json = null;
    try {
      json = JSON.parse(xhr.responseText);
    } 
    catch {
      json = null;
    }

    let list = [];

    if (Array.isArray(json)) 
      list = json;
    else if (json?.contacts) 
      list = json.contacts;
    else if (json?.results) 
      list = json.results;

    contacts = list;
    renderContacts();
  };

  xhr.send(JSON.stringify({}));
}

function saveContact(payload) {
  const xhr = new XMLHttpRequest();
  const route = editingId !== null ? "Update" : "Create";
  const url = `${urlBase}/${route}.${extension}`;
  const body = Object.assign({}, payload);

  if (editingId !== null) 
    body.contact_id = editingId;

  xhr.open("POST", url, true);
  xhr.withCredentials = true;
  xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
  xhr.onreadystatechange = function () {
    if (xhr.readyState !== 4) 
      return;
    if (xhr.status !== 200) {
      console.error(`[${route}] HTTP`, xhr.status);
      console.error(xhr.responseText);
      return;
    }
    loadContacts();
    closeModal();
  };
  xhr.send(JSON.stringify(body));
}

function deleteContact(id) {
  if (!confirm("Delete this contact?")) 
    return;

  const xhr = new XMLHttpRequest();
  const url = `${urlBase}/Delete.${extension}`;

  xhr.open("POST", url, true);
  xhr.withCredentials = true;
  xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
  xhr.onreadystatechange = function () {
    if (xhr.readyState !== 4) 
      return;
    if (xhr.status !== 200) {
      console.error("[Delete] HTTP", xhr.status);
      console.error(xhr.responseText);
      return;
    }
    loadContacts();
  };
  xhr.send(JSON.stringify({ contact_id: id }));
}

function doLogout(){
  const xhr = new XMLHttpRequest();
  const url = "backend/routes/Auth/Logout.php";

  xhr.open("POST", url, true);
  xhr.withCredentials = true;
  xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
  xhr.onreadystatechange = function () {
    if (xhr.readyState !== 4) 
      return;
    if (xhr.status !== 200) {
      console.error("[Logout] HTTP", xhr.status);
      console.error(xhr.responseText);
      return;
    }
    //redirects user to home page / login page after logging out successfully
    window.location.href = "index.html";
  };
  xhr.send(JSON.stringify({}));
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
  if (!c) 
    return;
  editingId = c.id;

  //fill inputs from contact object
  firstName.value = c.first_name ?? "";
  lastName.value = c.last_name ?? "";
  phoneNumber.value = c.phone_number ?? "";
  email.value = c.email ?? "";

  const title = document.getElementById("formTitle");
  if (title) 
    title.textContent = "Edit Contact";

  modal.classList.remove("hidden");

}

//close modal function
function closeModal() {
  modal.classList.add("hidden");
  editingId = null;
  form.reset();

  const title = document.getElementById("formTitle");
  if (title) 
    title.textContent = "Add Contact";
}

//contact rendering function - takes list of contacts to render, full list as default if called without argument
function renderContacts(list = contacts) {
  //clear the table body
  contactsBody.innerHTML = "";

  //go through every contact
  list.forEach(contact => {
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
      deleteContact(id);
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
  saveContact(payload);
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

  renderContacts(filtered);
};

//logout button functionality
logoutBtn.onclick = () => {
  doLogout();
};

//initial load of contacts for user when page opens
loadContacts();

// Allows the pop-up window to add contacts to be dragged around the screen by its header.
document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("modalWindow");
  const header = document.getElementById("modalHeader");

  let isDragging = false;
  let offsetX = 0;
  let offsetY = 0;

  // Start dragging when mouse is pressed on header
  header.addEventListener("mousedown", (e) => {
    isDragging = true;
    offsetX = e.clientX - modal.offsetLeft;
    offsetY = e.clientY - modal.offsetTop;
    header.style.cursor = "grabbing";
  });

  // Dragging motion when mouse moves
  document.addEventListener("mousemove", (e) => {
    if (!isDragging) return;

    modal.style.left = e.clientX - offsetX + "px";
    modal.style.top = e.clientY - offsetY + "px";
  });

  // Stop dragging when mouse is released
  document.addEventListener("mouseup", () => {
    isDragging = false;
    header.style.cursor = "grab";
  });
});

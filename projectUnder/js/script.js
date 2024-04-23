document.addEventListener('DOMContentLoaded', () => {
   let body = document.body;
   let profile = document.querySelector('.header .flex .profile');
   let searchForm = document.querySelector('.header .flex .search-form');
   let sideBar = document.querySelector('.side-bar');
   let toggleBtn = document.querySelector('#toggle-btn');
   let darkMode = localStorage.getItem('dark-mode');

   const userBtn = document.querySelector('#user-btn');
   const searchBtn = document.querySelector('#search-btn');
   const menuBtn = document.querySelector('#menu-btn');
   const closeSideBarBtn = document.querySelector('.side-bar .close-side-bar');

   if (userBtn) {
       userBtn.onclick = () => {
           profile.classList.toggle('active');
           if (searchForm) searchForm.classList.remove('active');
       };
   }

   if (searchBtn) {
       searchBtn.onclick = () => {
           if (searchForm) searchForm.classList.toggle('active');
           profile.classList.remove('active');
       };
   }

   if (menuBtn) {
       menuBtn.onclick = () => {
           sideBar.classList.toggle('active');
           body.classList.toggle('active');
       };
   }

   if (closeSideBarBtn) {
       closeSideBarBtn.onclick = () => {
           sideBar.classList.remove('active');
           body.classList.remove('active');
       };
   }

   document.querySelectorAll('input[type="number"]').forEach(InputNumber => {
       InputNumber.oninput = () => {
           if (InputNumber.value.length > InputNumber.maxLength) InputNumber.value = InputNumber.value.slice(0, InputNumber.maxLength);
       }
   });

   window.onscroll = () => {
       profile.classList.remove('active');
       if (searchForm) searchForm.classList.remove('active');

       if (window.innerWidth < 1200) {
           sideBar.classList.remove('active');
           body.classList.remove('active');
       }
   };

   const enableDarkMode = () => {
       toggleBtn.classList.replace('fa-sun', 'fa-moon');
       body.classList.add('dark');
       localStorage.setItem('dark-mode', 'enabled');
   };

   const disableDarkMode = () => {
       toggleBtn.classList.replace('fa-moon', 'fa-sun');
       body.classList.remove('dark');
       localStorage.setItem('dark-mode', 'disabled');
   };

   if (toggleBtn) {
       toggleBtn.onclick = (e) => {
           let darkMode = localStorage.getItem('dark-mode');
           if (darkMode === 'disabled') {
               enableDarkMode();
           } else {
               disableDarkMode();
           }
       };
   }

   const dropArea = document.getElementById('drop-area');
   const fileInput = document.getElementById('file-input');
   const fileList = document.getElementById('file-list');

   if (dropArea) {
       dropArea.addEventListener('dragenter', preventDefault, false);
       dropArea.addEventListener('dragover', preventDefault, false);
       dropArea.addEventListener('drop', preventDefault, false);
       dropArea.addEventListener('dragenter', highlight, false);
       dropArea.addEventListener('dragover', highlight, false);
       dropArea.addEventListener('dragleave', unhighlight, false);
       dropArea.addEventListener('drop', unhighlight, false);
       dropArea.addEventListener('drop', handleDrop, false);
   }

   if (fileInput) {
       fileInput.addEventListener('change', handleFileInputChange, false);
   }

   function preventDefault(e) {
       e.preventDefault();
       e.stopPropagation();
   }

   function highlight() {
       dropArea.classList.add('highlight');
   }

   function unhighlight() {
       dropArea.classList.remove('highlight');
   }

   function handleDrop(e) {
       unhighlight();
       const files = e.dataTransfer.files;
       handleFiles(files);
   }

   function handleFileInputChange() {
       const files = fileInput.files;
       handleFiles(files);
   }

   function handleFiles(files) {
       for (const file of files) {
           displayFile(file);
       }
   }

   function displayFile(file) {
       const listItem = document.createElement('li');
       listItem.classList.add('file-item');
       listItem.textContent = file.name;
       fileList.appendChild(listItem);
   }
});

 document.addEventListener("DOMContentLoaded", function() {
   let logoutBtn = document.querySelector("#logout");
   if (logoutBtn) {
       logoutBtn,addEventListener("click", function () {
       sessionStorage.clear();
window.location.href = "index.html";
   });
}

   function validate(form) {
    let fail = "";
   fail += validateEmail(form.email.value);
fail += validatePassword(form.password.value);

            if (fail === "") return true;
            else  { 
alert(fail); 
                return false;
            }
       }
        
       function validateEmail(field) {
            if (field === "") return "No Email was entered.\n";
            else if (!field.match(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/)) 
                return "The Email address is invalid.\n"
            return "";
         }
        
         function validatePassword(field) {
            if (field === "") return "No Password was entered.\n";
             else if (field.length < 9)
                return "Password must be at least 9 characters.\n";
            else if (!/[a-z]/.test(field) || !/[A-Z]/.test(field) || !/[0-9]/.test(field))
                return "Password requires one each of a-z, A-Z, and 0-9.\n";
            return "";
         }

        function showBookDetails(title, author, genre, description, pdfUrl) {
                     document.getElementById("book-title").innerText = title;
                                  document.getElementById("book-author").innerText = author;
             document.getElementById("book-genre").innerText = genre;
             document.getElementById("book-description").innerText = description;
             document.getElementById('book-pdf').setAttribute('src', pdfUrl);
         }

     //*Codes taken from ChatGPT *//

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".borrow-btn").forEach(button => {
        button.addEventListener("click", function () {
            let bookId = this.getAttribute("data-id");
           fetch("borrow.php", {
            method: "POST",
            headers: {"Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ book_id: bookId })
           })
           .then(response => response.text())
           .then(alert)
           .catch(error => console.error("Error:", error));
           }); 
    });
    document.querySelectorAll(".return-btn").forEach(button => {
        button.addEventListener("click", function () {
            let bookId = this.getAttribute("data-id");
            fetch("return.php", {
                method: "POST",
                headers: {"Content-Type": "application/x-www-form-urlencoded"},
                body: new URLSearchParams({ book_id: bookId }) 
            })
            .then(response => response.text())
            .then(alert)
            .catch(error => console.error("Error:", error));
            });
        });
    });
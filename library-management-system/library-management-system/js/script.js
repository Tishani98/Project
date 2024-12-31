document.addEventListener("DOMContentLoaded", function() {
    fetchBooks();

    window.openAddBookForm = function(id = null, title = '', author = '', genre = '', published_year = '', stock = '') {
        document.getElementById('bookForm').style.display = 'block';
        document.getElementById('formTitle').innerText = id ? 'Edit Book' : 'Add Book';
        document.getElementById('bookId').value = id || '';
        document.getElementById('title').value = title;
        document.getElementById('author').value = author;
        document.getElementById('genre').value = genre;
        document.getElementById('published_year').value = published_year;
        document.getElementById('stock').value = stock;
    };

    window.closeForm = function() {
        document.getElementById('bookForm').style.display = 'none';
    };

    window.saveBook = function() {
        var id = document.getElementById('bookId').value;
        var title = document.getElementById('title').value;
        var author = document.getElementById('author').value;
        var genre = document.getElementById('genre').value;
        var published_year = document.getElementById('published_year').value;
        var stock = document.getElementById('stock').value;

        var xhr = new XMLHttpRequest();
        var url = id ? 'php/api.php?action=update' : 'php/api.php?action=add';
        var method = id ? 'PUT' : 'POST';

        xhr.open(method, url, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
            if (xhr.status === 200) {
                fetchBooks();
                closeForm();
            }
        };
        xhr.send('id=' + id + '&title=' + title + '&author=' + author + '&genre=' + genre + '&published_year=' + published_year + '&stock=' + stock);
    };

    window.deleteBook = function(id) {
        var xhr = new XMLHttpRequest();
        xhr.open('DELETE', 'php/api.php?action=delete&id=' + id, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                fetchBooks();
            }
        };
        xhr.send();
    };

    function fetchBooks() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'php/api.php?action=fetch', true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                var books = JSON.parse(xhr.responseText);
                var booksTable = document.getElementById('booksTable').getElementsByTagName('tbody')[0];
                booksTable.innerHTML = '';
                books.forEach(function(book) {
                    var row = booksTable.insertRow();
                    row.insertCell(0).textContent = book.id;
                    row.insertCell(1).textContent = book.title;
                    row.insertCell(2).textContent = book.author;
                    row.insertCell(3).textContent = book.genre;
                    row.insertCell(4).textContent = book.published_year;
                    row.insertCell(5).textContent = book.stock;
                    var actionCell = row.insertCell(6);
                    actionCell.innerHTML = `<button onclick="openAddBookForm(${book.id}, '${book.title}', '${book.author}', '${book.genre}', '${book.published_year}', '${book.stock}')">Edit</button> <button onclick="deleteBook(${book.id})">Delete</button>`;
                });
            }
        };
        xhr.send();
    }
});

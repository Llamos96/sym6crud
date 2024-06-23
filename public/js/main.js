
const articles = document.getElementById('articles-table');

if (articles) {
    articles.addEventListener('click', event => {
        if(event.target.className === 'btn btn-danger delete-article') {
            if (confirm('Are you sure?')) {
                const id = event.target.getAttribute('data-id');

                // console.log(id);
                fetch(`/article/delete/${id}`, {
                    method: 'DELETE'
                }).then(res => window.location.reload());
            }
        }
    })
}
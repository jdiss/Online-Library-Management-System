<label>Author<span style="color:red;">*</span></label>
<input type="text" class="form-control" name="author" id="author" required="required"
    placeholder="Type to search or create author" onkeyup="fetchAuthors(this.value)" onblur="hideAuthors()" value="<?php echo htmlentities($authorName); ?>" />
<div id="authorSuggestions" class="suggestions"></div>
<script>
    function fetchAuthors(query) {
        if (query.length < 2) {
            document.getElementById('authorSuggestions').innerHTML = '';
            return;
        }
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'fetch_authors.php?author=' + encodeURIComponent(query), true);
        xhr.onload = function () {
            if (this.status == 200) {
                var suggestions = JSON.parse(this.responseText);
                var suggestionsHtml = '';
                suggestions.forEach(function (author) {
                    suggestionsHtml += '<div class="suggestion-item" onclick="selectAuthor(\'' + author.name + '\', ' + author.id + ')">' + author.name + '</div>';
                });
                document.getElementById('authorSuggestions').innerHTML = suggestionsHtml;
            }
        };
        xhr.send();
    }

    function hideAuthors(){
        document.getElementById('categoryAuthor').innerHTML = '';
    }

    function selectAuthor(name, id) {
        document.getElementById('author').value = name;
        document.getElementById('authorId').value = id;
        document.getElementById('authorSuggestions').innerHTML = '';

    }
</script>
<input type="hidden" name="authorId" id="authorId" value="<?php echo htmlentities($authorId); ?>" />
<style>
    .suggestions {
        max-height: 150px;
        overflow-y: auto;
        position: absolute;
        z-index: 1000;
        background: white;
        width: 85%;
    }

    .suggestion-item {
        padding: 10px;
        cursor: pointer;
        border-bottom: 1px dotted #ccc;
        border-left: 1px dotted #ccc;
        border-right: 1px dotted #ccc;
        width: 100%;
    }

    .suggestion-item:hover {
        background-color: #f0f0f0;
    }
</style>
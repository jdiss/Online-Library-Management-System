<label> Category<span style="color:red;">*</span></label>
<input type="text" class="form-control" name="category" id="category" required="required"
    placeholder="Type to search or create category" onkeyup="fetchCategories(this.value)"  value="<?php echo htmlentities($categoryName); ?>" />
<div id="categorySuggestions" class="suggestions"></div>
<script>
    function fetchCategories(query) {
        if (query.length < 2) {
            document.getElementById('categorySuggestions').innerHTML = '';
            return;
        }
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'fetch_categories.php?category=' + encodeURIComponent(query), true);
        xhr.onload = function () {
            if (this.status == 200) {
                var suggestions = JSON.parse(this.responseText);
                var suggestionsHtml = '';
                suggestions.forEach(function (category) {
                    suggestionsHtml += '<div class="suggestion-item" onclick="selectCategory(\'' + category.name + '\', ' + category.id + ')">' + category.name + '</div>';
                });
                document.getElementById('categorySuggestions').innerHTML = suggestionsHtml;
            }
        };
        xhr.send();
    }

    function selectCategory(name, id) {
        document.getElementById('category').value = name;
        document.getElementById('categoryId').value = id;
        document.getElementById('categorySuggestions').innerHTML = '';

    }
</script>
<input type="hidden" name="categoryId" id="categoryId" value="<?php echo htmlentities($categoryId); ?>" />
<style>
    .suggestions {
        max-height: 150px;
        overflow-y: auto;
        position: absolute;
        z-index: 1000;
        background: white;
        width: 80%;
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
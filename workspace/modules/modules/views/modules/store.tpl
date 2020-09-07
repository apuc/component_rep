{core\App::$breadcrumbs->addItem(['text' => 'Create'])}
{*<div class="h1">{$h1}</div>*}

<div class="container">
    <form class="form-horizontal" name="create_form" id="create_form" method="post" action="/admin/modules/create">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" class="form-control"  required="required" />
        </div>

        <div class="form-group">
            <label for="version">Version:</label>
            <input type="text" name="version" id="version" class="form-control"  required="required" />
        </div>

        <div class="form-group">
            <label for="type">Type:</label>
            <input type="text" name="type" id="type" class="form-control"  required="required" />
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <input type="text" name="description" id="description" class="form-control"  required="required" />
        </div>

        <div class="form-group">
            <label for="user_id">User_id:</label>
            <input type="text" name="user_id" id="user_id" class="form-control"  required="required" />
        </div>


        <div class="form-group">
            <input type="submit" name="submit" id="submit_button" class="btn btn-dark" value="Submit">
        </div>
    </form>
</div>
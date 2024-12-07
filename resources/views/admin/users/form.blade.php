<div class="form-group">
    <label for="exampleInputEmail1">Name</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter name"
        name="name" value="{{ isset($user) ? $user->name : '' }}">
    <small id="emailHelp" class="form-text text-muted">Name of the menu</small>
</div>
<div class="form-group">
    <label for="exampleInputEmail12">Email</label>
    <input type="text" class="form-control" id="exampleInputEmail12" aria-describedby="emailHelp"
        placeholder="Enter email" name="email" value="{{ isset($user) ? $user->email : '' }}">
    <small id="emailHelp" class="form-text text-muted">email of the user</small>
</div>
<div class="form-group">
    <label for="exampleFormControlSelect12">Role</label>
    <select class="form-control" name="is_admin" id="exampleFormControlSelect12">
        <option {{ isset($user) && $user->is_admin ? "selected" : "" }} value="0">Normal user</option>
        <option {{ isset($user) && $user->is_admin ? "selected" : "" }} value="1">Admin</option>
    </select>
</div>
<div class="form-group">
    <label for="exampleFormControlSelect12">See edit button</label>
    <select class="form-control" name="see_edit_button_on_texts" id="exampleFormControlSelect12">
        <option {{ isset($user) && $user->see_edit_button_on_texts ? "selected" : "" }} value="0">No</option>
        <option {{ isset($user) && $user->see_edit_button_on_texts ? "selected" : "" }} value="1">Yes</option>
    </select>
    <small id="Repeat passwordHelp" class="form-text text-muted">See edit button on texts in front-page</small>
</div>
<div class="form-group">
    <label for="exampleInputEmail12">Password</label>
    <input type="password" class="form-control" id="exampleInputpassword12" aria-describedby="passwordHelp"
        placeholder="Enter password" name="password" value="">
    <small id="passwordHelp" class="form-text text-muted">Password of the user</small>
</div>
<div class="form-group">
    <label for="exampleInputEmail12">Repeat password</label>
    <input type="password" class="form-control" id="exampleInputRepeat password12" aria-describedby="Repeat passwordHelp"
        placeholder="Enter Repeat password" name="repeat_password" value="">
    <small id="Repeat passwordHelp" class="form-text text-muted">Repeat password of the user</small>
</div>

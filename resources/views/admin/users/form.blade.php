<div class="form-group">
    <label for="exampleInputEmail1">Name</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter name"
        name="name" value="{{ old('name', isset($user) ? $user->name : '') }}" required>
    <small id="emailHelp" class="form-text text-muted">Name of the user</small>
    @error('name')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
<div class="form-group">
    <label for="exampleInputEmail12">Email</label>
    <input type="email" class="form-control" id="exampleInputEmail12" aria-describedby="emailHelp"
        placeholder="Enter email" name="email" value="{{ old('email', isset($user) ? $user->email : '') }}" required>
    <small id="emailHelp" class="form-text text-muted">Email of the user</small>
    @error('email')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
<div class="form-group">
    <label for="exampleFormControlSelect12">Role</label>
    <select class="form-control" name="role" id="exampleFormControlSelect12" required>
        <option value="">Select a role</option>
        @foreach ($roles as $role)
            @php
                $isSelected = false;
                if (old('role')) {
                    $isSelected = old('role') == $role->name;
                } elseif (isset($user)) {
                    $isSelected = $user->hasRole($role->name);
                }
            @endphp
            <option value="{{ $role->name }}" {{ $isSelected ? 'selected' : '' }}>
                {{ ucfirst($role->name) }}
            </option>
        @endforeach
    </select>
    @error('role')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
<div class="form-group">
    <label for="exampleFormControlSelect13">See edit button</label>
    <select class="form-control" name="see_edit_button_on_texts" id="exampleFormControlSelect13">
        <option {{ old('see_edit_button_on_texts', isset($user) && !$user->show_edit_button_on_texts ? '1' : '0') == '0' ? "selected" : "" }} value="0">No</option>
        <option {{ old('see_edit_button_on_texts', isset($user) && $user->show_edit_button_on_texts ? '1' : '0') == '1' ? "selected" : "" }} value="1">Yes</option>
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

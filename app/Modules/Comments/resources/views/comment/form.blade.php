<form action="{{ $comment ? route('admin.comments.update', $comment) : route('admin.comments.store') }}" method="POST">
    @csrf
    @if ($comment)
        @method('PUT')
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="commentable_type">Content Type</label>
                <input class="form-control" name="commentable_type" required value="{{ $comment?->commentable_type }}">
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="commentable_id">Content ID</label>
                <input type="number" class="form-control @error('commentable_id') is-invalid @enderror"
                    id="commentable_id" name="commentable_id"
                    value="{{ old('commentable_id', $comment->commentable_id ?? '') }}" required>
                @error('commentable_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="user_id">User Id</label>
                <input type="text" class="form-control @error('user_id') is-invalid @enderror" name="user_id"
                    value="{{ $comment?->user_id }}">
                @error('user_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="stars">Rating</label>
                <select class="form-control @error('stars') is-invalid @enderror" id="stars" name="stars">
                    <option value="">No Rating</option>
                    @for ($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}"
                            {{ old('stars', $comment->stars ?? '') == $i ? 'selected' : '' }}>
                            {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                        </option>
                    @endfor
                </select>
                @error('stars')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Guest Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                    name="name" value="{{ old('name', $comment->name ?? '') }}">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="email">Guest Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                    name="email" value="{{ old('email', $comment->email ?? '') }}">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="content">Comment Text</label>
        <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="6"
            required>{{ old('content', !empty($comment->getTranslation('content', app()->getLocale(), false)) ? $comment->getTranslation('content', app()->getLocale(), false) : '--The language is wrong. Please change the language--') }}</textarea>
        @error('content')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="status">Status</label>
        <select name="status" id="status" class="form-control">
            <option value="pending" {{ old('status', $comment->status ?? 'pending') == 'pending' ? 'selected' : '' }}>
                Pending
            </option>
            <option value="approved" {{ old('status', $comment->status) == 'approved' ? 'selected' : '' }}>
                Approved
            </option>
            <option value="rejected" {{ old('status', $comment->status) == 'rejected' ? 'selected' : '' }}>
                Rejected
            </option>
            <option value="spam" {{ old('status', $comment->status) == 'spam' ? 'selected' : '' }}>Spam
            </option>
            <option value="hidden" {{ old('status', $comment->status) == 'hidden' ? 'selected' : '' }}>Hidden
            </option>
            <option value="deleted" {{ old('status', $comment->status) == 'deleted' ? 'selected' : '' }}>Deleted
            </option>
        </select>
        @error('status')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> {{ $comment ? 'Update Comment' : 'Create Comment' }}
        </button>
        <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary">
            <i class="fas fa-times"></i> Cancel
        </a>
    </div>
</form>

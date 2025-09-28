<style>
    #controlSidebar {
        width: 300px;
        /* default width */
        /* min-width: 200px; */
        /* max-width: 600px; */
        position: fixed;
        /* AdminLTE already uses fixed */
        top: 0;
        right: 0;
        /* height: 100%; */
    }

    #controlSidebar .resize-handle {
        width: 5px;
        cursor: ew-resize;
        background: rgba(255, 255, 255, 0.1);
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
    }
</style>
<aside class="control-sidebar control-sidebar-dark" id="controlSidebar">
    <div class="resize-handle" style="">
        <i class=" fas fa-grip-lines-vertical text-light"></i>
    </div>
    <!-- Control sidebar content goes here -->
    <div class="p-0 h-100 d-flex flex-column">
        <!-- AI Header -->
        <div class="bg-gradient-primary p-3 border-bottom">
            <h5 class="text-white mb-0">
                <i class="fas fa-robot mr-2"></i>
                AI Assistant
            </h5>
            <small class="text-light">Chat with AI personas</small>
        </div>

        <!-- AI Controls -->
        <div class="flex-grow-1 d-flex flex-column">
            <!-- Persona Selection -->
            <div class="p-3 border-bottom">
                <label class="text-light mb-2">
                    <i class="fas fa-mask mr-1"></i>
                    Select Persona
                </label>
                <select id="ai-persona-select" class="form-control form-control-sm bg-dark text-light border-secondary">
                    <option value="">Default Assistant</option>
                    <option value="1">Code Reviewer</option>
                    <option value="2">Creative Writer</option>
                    <option value="3">Technical Expert</option>
                </select>
                <button id="manage-personas-btn" class="btn btn-outline-light btn-xs mt-2">
                    <i class="fas fa-cog"></i> Manage Personas
                </button>
            </div>

            <!-- Chat Selection -->
            <div class="p-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="text-light mb-0">
                        <i class="fas fa-comments mr-1"></i>
                        Chat Sessions
                    </label>
                    <button id="new-chat-btn" class="btn btn-success btn-xs">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>

                <div id="chat-list" class="chat-list" style="max-height: 200px; overflow-y: auto;">
                    <!-- Chat items will be loaded here -->
                    {{-- <div class="chat-item p-2 mb-1 bg-secondary rounded cursor-pointer" data-chat-id="1">
                        <div class="d-flex justify-content-between">
                            <small class="text-light font-weight-bold">Debug Session</small>
                            <small class="text-muted">2m ago</small>
                        </div>
                        <small class="text-muted">Help me debug this Laravel issue...</small>
                    </div>
                    <div class="chat-item p-2 mb-1 bg-dark rounded cursor-pointer" data-chat-id="2">
                        <div class="d-flex justify-content-between">
                            <small class="text-light font-weight-bold">Code Review</small>
                            <small class="text-muted">1h ago</small>
                        </div>
                        <small class="text-muted">Can you review this function...</small>
                    </div> --}}
                </div>
            </div>

            <!-- Chat Messages Area -->
            <div class="flex-grow-1 d-flex flex-column">
                <div class="p-2 bg-secondary">
                    <small class="text-light">
                        <i class="fas fa-circle text-success"></i>
                        <span id="current-chat-name">Debug Session</span>
                        <span class="float-right">
                            <span id="token-count" class="badge badge-info">1.2k tokens</span>
                            <span id="cost-display" class="badge badge-warning">$0.003</span>
                        </span>
                    </small>
                </div>

                <!-- Messages Container -->
                <div id="chat-messages" class="flex-grow-1 p-2"
                    style="height: 300px; overflow-y: auto; background: rgba(0,0,0,0.1);">
                    <!-- Messages will be loaded here -->
                    <div class="message mb-2">
                        <div class="user-message bg-primary text-white p-2 rounded ml-4">
                            <small class="d-block mb-1 opacity-75">You • 2 minutes ago</small>
                            Help me debug this Laravel controller issue
                        </div>
                    </div>

                    <div class="message mb-2">
                        <div class="ai-message bg-secondary text-light p-2 rounded mr-4">
                            <small class="d-block mb-1 opacity-75">
                                <i class="fas fa-robot"></i> AI Assistant • 2 minutes ago
                            </small>
                            I'd be happy to help you debug your Laravel controller. Could you please share the
                            specific error message and the relevant code?
                        </div>
                    </div>
                </div>

                <!-- Message Input -->
                <div class="p-2 border-top">
                    <div class="input-group">
                        <textarea id="ai-message-input" class="form-control form-control-sm bg-dark text-light border-secondary" rows="2"
                            placeholder="Type your message..." style="resize: none;"></textarea>
                        <div class="input-group-append">
                            <button id="send-message-btn" class="btn btn-primary btn-sm">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                        <small class="text-muted">
                            <span id="char-count">0</span>/1000
                        </small>
                        <div>
                            <button id="clear-chat-btn" class="btn btn-outline-danger btn-xs">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                            <button id="export-chat-btn" class="btn btn-outline-info btn-xs" style="display: none">
                                <i class="fas fa-download"></i> Export
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</aside>
<!-- /.control-sidebar -->

<!-- AI Persona Management Modal -->
<div class="modal fade" id="persona-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-light">
                    <i class="fas fa-mask mr-2"></i>
                    Manage AI Personas
                </h5>
                <button type="button" class="close text-light" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <h6 class="text-light">Available Personas</h6>
                        <div id="persona-list" class="list-group">
                            <!-- Personas will be loaded here -->
                        </div>
                        <button id="new-persona-btn" class="btn btn-success btn-sm mt-2 w-100">
                            <i class="fas fa-plus"></i> New Persona
                        </button>
                    </div>
                    <div class="col-md-8">
                        <form id="persona-form">
                            @csrf

                            <div class="form-group">
                                <label for="persona-name" class="text-light">Persona Name</label>
                                <input type="text" id="persona-name"
                                    class="form-control bg-dark text-light border-secondary"
                                    placeholder="Enter persona name">
                            </div>

                            <div class="form-group">
                                <label for="persona-description" class="text-light">Description</label>
                                <textarea id="persona-description" class="form-control bg-dark text-light border-secondary" rows="2"
                                    placeholder="Brief description of the persona"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="persona-prompt" class="text-light">System Prompt</label>
                                <textarea id="persona-prompt" class="form-control bg-dark text-light border-secondary" rows="4"
                                    placeholder="Enter the system prompt that defines this persona's behavior"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="persona-model" class="text-light">Suggested Model</label>
                                <select id="persona-model" class="form-control bg-dark text-light border-secondary">
                                    <option value="gpt-5">GPT-5</option>
                                    <option value="gpt-5-mini">GPT-5 Mini</option>
                                    <option value="gpt-5-nano">GPT-5 Nano</option>
                                    <option value="gpt-4-turbo">GPT-4 Turbo</option>
                                    <option value="gpt-4">GPT-4</option>
                                    <option value="gpt-3.5-turbo">GPT-3.5 Turbo</option>
                                </select>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" id="persona-public" class="form-check-input">
                                <label for="persona-public" class="form-check-label text-light">Make this
                                    persona public</label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="save-persona-btn" class="btn btn-primary">Save AI Persona</button>
            </div>
        </div>
    </div>
</div>

<!-- Add this CSS for better styling -->
<style>
    /* .control-sidebar {
            width: 400px !important;
        } */

    .chat-item:hover {
        background-color: #495057 !important;
    }

    .chat-item.active {
        background-color: #007bff !important;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    .message .user-message {
        margin-left: 60px;
        position: relative;
    }

    .message .ai-message {
        margin-right: 60px;
        position: relative;
    }

    #chat-messages::-webkit-scrollbar {
        width: 6px;
    }

    #chat-messages::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.1);
    }

    #chat-messages::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 3px;
    }

    .chat-list::-webkit-scrollbar {
        width: 4px;
    }

    .chat-list::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.1);
    }

    .chat-list::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 2px;
    }

    .bg-gradient-primary {
        background: linear-gradient(45deg, #007bff, #0056b3) !important;
    }

    .typing-animation {
        display: inline-flex;
        align-items: center;
    }

    .typing-animation span {
        height: 8px;
        width: 8px;
        background-color: #6c757d;
        border-radius: 50%;
        display: inline-block;
        margin: 0 1px;
        animation: typing 1.4s infinite ease-in-out;
    }

    .typing-animation span:nth-child(1) {
        animation-delay: -0.32s;
    }

    .typing-animation span:nth-child(2) {
        animation-delay: -0.16s;
    }

    @keyframes typing {

        0%,
        80%,
        100% {
            transform: scale(0.8);
            opacity: 0.5;
        }

        40% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .system-message {
        margin: 0 20px;
    }


    /* start highlight js */
    .message-content pre {
        margin: 10px 0;
        background: rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 4px;
        overflow-x: auto;
    }

    .message-content pre code {
        padding: 12px;
        display: block;
        font-family: 'Courier New', Monaco, 'Lucida Console', monospace;
        font-size: 0.875rem;
        line-height: 1.4;
        white-space: pre;
        overflow-wrap: normal;
        word-break: normal;
    }

    .message-content .inline-code {
        background: rgba(0, 0, 0, 0.2);
        padding: 2px 4px;
        border-radius: 3px;
        font-family: 'Courier New', Monaco, 'Lucida Console', monospace;
        font-size: 0.875em;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .user-message .inline-code {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.3);
    }

    .ai-message .inline-code {
        background: rgba(0, 0, 0, 0.3);
        border-color: rgba(255, 255, 255, 0.1);
    }

    /* Ensure highlight.js styles work in dark theme */
    .hljs {
        background: rgba(0, 0, 0, 0.3) !important;
        color: #f8f8f2 !important;
    }

    /* end highlight js */
</style>

<!-- Add JavaScript for AI functionality -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('ai-box').addEventListener('click', async () => {
            if (!document.getElementById('hljs-css')) {
                const hljsCss = document.createElement('link');
                hljsCss.id = 'hljs-css';
                hljsCss.rel = 'stylesheet';
                hljsCss.href = '/highlight/styles/panda-syntax-dark.min.css';
                document.head.appendChild(hljsCss);
            }

            // Dynamically load Highlight.js JS
            if (!window.hljs) {
                await new Promise((resolve, reject) => {
                    const hljsScript = document.createElement('script');
                    hljsScript.src = '/highlight/highlight.min.js';
                    hljsScript.onload = resolve;
                    hljsScript.onerror = reject;
                    document.body.appendChild(hljsScript);
                });
            }

            // AI Chat functionality
            let currentChatId = null;
            let selectedPersonaId = null;

            // Character counter
            const messageInput = document.getElementById('ai-message-input');
            const charCount = document.getElementById('char-count');

            messageInput.addEventListener('input', function() {
                const length = this.value.length;
                charCount.textContent = length;

                if (length > 1000) {
                    charCount.classList.add('text-danger');
                } else {
                    charCount.classList.remove('text-danger');
                }
            });

            // Send message
            document.getElementById('send-message-btn').addEventListener('click', function() {
                sendMessage();
            });

            messageInput.addEventListener('keypress', function(e) {
                if (e.which === 13 && !e.shiftKey) {
                    e.preventDefault();
                    sendMessage();
                }
            });

            function sendMessage() {
                const message = messageInput.value.trim();
                if (!message) return;

                // Check if we have a chat session
                if (!currentChatId) {
                    // Create new chat first
                    createNewChat().then(() => {
                        sendMessageToAI(message);
                    });
                    return;
                }

                sendMessageToAI(message);
            }

            async function sendMessageToAI(message) {
                // Add user message to chat
                addMessage('user', message);

                // Clear input
                messageInput.value = '';
                charCount.textContent = '0';

                // Show typing indicator
                showTypingIndicator();

                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')
                        ?.getAttribute('content');

                    const response = await fetch(
                        `/admin/ai-chats/${currentChatId}/send-message`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                message: message,
                                persona_id: selectedPersonaId
                            })
                        });

                    // Remove typing indicator
                    const typingIndicator = document.querySelector('.typing-indicator');
                    if (typingIndicator) {
                        typingIndicator.remove();
                    }

                    if (response.ok) {
                        const data = await response.json();

                        // Add AI response
                        addMessage('assistant', data.response);

                        // Update token count and cost if provided
                        if (data.tokens) {
                            document.getElementById('token-count').textContent =
                                `${data.tokens} tokens`;
                        }
                        if (data.cost) {
                            document.getElementById('cost-display').textContent =
                                `$${data.cost}`;
                        }
                    } else {
                        const errorData = await response.json();
                        toastr.error(errorData.message || 'Failed to send message');

                        // Add error message to chat
                        addMessage('system',
                            'Sorry, I encountered an error processing your message. Please try again.'
                        );
                    }
                } catch (error) {
                    console.error('Error sending message:', error);

                    // Remove typing indicator
                    const typingIndicator = document.querySelector('.typing-indicator');
                    if (typingIndicator) {
                        typingIndicator.remove();
                    }

                    toastr.error('Network error sending message');
                    addMessage('system',
                        'Network error. Please check your connection and try again.');
                }
            }

            // Also update the persona selection to store the selected value
            document.getElementById('ai-persona-select').addEventListener('change', function() {
                selectedPersonaId = this.value || null;
            });

            // Chat selection
            document.addEventListener('click', function(e) {
                if (e.target.closest('.chat-item')) {
                    const chatItem = e.target.closest('.chat-item');

                    // Remove active class from all chat items
                    document.querySelectorAll('.chat-item').forEach(item => {
                        item.classList.remove('active');
                    });

                    // Add active class to clicked item
                    chatItem.classList.add('active');

                    currentChatId = chatItem.dataset.chatId;

                    const chatName = chatItem.querySelector('.font-weight-bold')
                        .textContent;
                    document.getElementById('current-chat-name').textContent = chatName;

                    // Load chat messages
                    loadChatMessages(currentChatId);
                }
            });

            // Persona management
            document.getElementById('manage-personas-btn').addEventListener('click', function() {
                // Show modal (assuming you're using Bootstrap)
                const modal = document.getElementById('persona-modal');
                if (window.bootstrap) {
                    new bootstrap.Modal(modal).show();
                } else if (window.jQuery) {
                    jQuery(modal).modal('show');
                }
                // Load personas
                loadPersonas();
            });

            // New chat
            document.getElementById('new-chat-btn').addEventListener('click', function() {
                createNewChat();
            });

            // Clear chat
            document.getElementById('clear-chat-btn').addEventListener('click', async function() {
                if (confirm('Are you sure you want to clear this chat?')) {
                    document.getElementById('chat-messages').innerHTML = '';
                    // Clear chat on backend
                    if (currentChatId) {
                        clearChat(currentChatId);
                        await loadChats();
                    }
                }
            });

            // Export chat
            document.getElementById('export-chat-btn').addEventListener('click', function() {
                if (currentChatId) {
                    exportChat(currentChatId);
                } else {
                    toastr.warning('Please select a chat to export');
                }
            });

            // Add this to the existing JavaScript section, after the other event listeners
            let currentPersonaId = null;

            // Save persona button
            document.getElementById('save-persona-btn').addEventListener('click', function() {
                savePersona();
            });

            // New persona button
            document.getElementById('new-persona-btn').addEventListener('click', function() {
                console.log('New persona button')
                clearPersonaForm();
                currentPersonaId = null;
            });

            // Persona list item click handler
            document.addEventListener('click', function(e) {
                if (e.target.closest('.persona-item')) {
                    const personaItem = e.target.closest('.persona-item');
                    const personaId = personaItem.dataset.personaId;
                    loadPersonaForEdit(personaId);
                }

                // Delete persona button
                if (e.target.closest('.delete-persona-btn')) {
                    console.log('Delete persona button')
                    e.stopPropagation();
                    const personaId = e.target.closest('.delete-persona-btn').dataset
                        .personaId;
                    deletePersona(personaId);
                }
            });

            async function savePersona() {
                const name = document.getElementById('persona-name').value.trim();
                const description = document.getElementById('persona-description').value.trim();
                const prompt = document.getElementById('persona-prompt').value.trim();
                const model = document.getElementById('persona-model').value;
                const isPublic = document.getElementById('persona-public').checked;

                // Validation
                if (!name) {
                    toastr.error('Persona name is required');
                    return;
                }

                if (!prompt) {
                    toastr.error('System prompt is required');
                    return;
                }

                const saveBtn = document.getElementById('save-persona-btn');
                const originalText = saveBtn.textContent;
                saveBtn.textContent = 'Saving...';
                saveBtn.disabled = true;

                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')
                        ?.getAttribute('content');

                    const requestData = {
                        name: name,
                        description: description,
                        system_prompt: prompt,
                        suggested_model: model,
                        is_public: isPublic
                    };

                    let url, method;
                    if (currentPersonaId) {
                        // Update existing persona
                        url = `/admin/ai-personas/${currentPersonaId}`;
                        method = 'PUT';
                    } else {
                        // Create new persona
                        url = '/admin/ai-personas';
                        method = 'POST';
                    }

                    const response = await fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            // 'X-CSRF-TOKEN': csrfToken
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        body: JSON.stringify(requestData)
                    });

                    if (response.ok) {
                        const data = await response.json();

                        if (currentPersonaId) {
                            toastr.success('Persona updated successfully!');
                        } else {
                            toastr.success('Persona created successfully!');
                            currentPersonaId = data.persona.id;
                        }

                        // Reload personas list
                        loadPersonas();

                        // Close modal after a short delay
                        setTimeout(() => {
                            const modal = document.getElementById('persona-modal');
                            if (window.bootstrap) {
                                bootstrap?.Modal?.getInstance(modal)?.hide();
                            } else if (window.jQuery) {
                                jQuery(modal)?.modal('hide');
                            }
                        }, 1000);

                    } else {
                        const errorData = await response.json();

                        if (response.status === 422 && errorData.errors) {
                            // Validation errors
                            const firstError = Object.values(errorData.errors)[0][0];
                            toastr.error(firstError);
                        } else {
                            toastr.error(errorData.message || 'Failed to save persona');
                        }
                    }
                } catch (error) {
                    console.error('Error saving persona:', error);
                    toastr.error('Network error saving persona');
                } finally {
                    saveBtn.textContent = originalText;
                    saveBtn.disabled = false;
                }
            }

            async function loadPersonaForEdit(personaId) {
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')
                        ?.getAttribute('content');

                    const response = await fetch(`/admin/ai-personas/${personaId}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });

                    if (response.ok) {
                        const persona = await response.json();

                        // Populate form
                        document.getElementById('persona-name').value = persona.name || '';
                        document.getElementById('persona-description').value = persona
                            .description || '';
                        document.getElementById('persona-prompt').value = persona
                            .system_prompt ||
                            '';
                        document.getElementById('persona-model').value = persona
                            .suggested_model ||
                            'gpt-3.5-turbo';
                        document.getElementById('persona-public').checked = persona.is_public ||
                            false;

                        currentPersonaId = persona.id;

                        // Update persona list selection
                        document.querySelectorAll('.persona-item').forEach(item => {
                            item.classList.remove('active');
                        });

                        const selectedItem = document.querySelector(
                            `[data-persona-id="${personaId}"]`);
                        if (selectedItem) {
                            selectedItem.classList.add('active');
                        }

                    } else {
                        toastr.error('Failed to load persona details');
                    }
                } catch (error) {
                    console.error('Error loading persona:', error);
                    toastr.error('Network error loading persona');
                }
            }

            async function deletePersona(personaId) {
                if (!confirm(
                        'Are you sure you want to delete this persona? This action cannot be undone.'
                    )) {
                    return;
                }

                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')
                        ?.getAttribute('content');

                    const response = await fetch(`/admin/ai-personas/${personaId}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });

                    if (response.ok) {
                        toastr.success('Persona deleted successfully!');

                        // Remove from list
                        const personaItem = document.querySelector(
                            `[data-persona-id="${personaId}"]`);
                        if (personaItem) {
                            personaItem.remove();
                        }

                        // Clear form if this persona was being edited
                        if (currentPersonaId == personaId) {
                            clearPersonaForm();
                            currentPersonaId = null;
                        }

                        // Reload personas to update dropdown
                        loadPersonas();

                    } else {
                        const errorData = await response.json();

                        if (response.status === 409) {
                            toastr.error('Cannot delete persona that is being used in chats');
                        } else {
                            toastr.error(errorData.message || 'Failed to delete persona');
                        }
                    }
                } catch (error) {
                    console.error('Error deleting persona:', error);
                    toastr.error('Network error deleting persona');
                }
            }

            function clearPersonaForm() {
                document.getElementById('persona-name').value = '';
                document.getElementById('persona-description').value = '';
                document.getElementById('persona-prompt').value = '';
                document.getElementById('persona-model').value = 'gpt-3.5-turbo';
                document.getElementById('persona-public').checked = false;

                // Remove active class from all persona items
                document.querySelectorAll('.persona-item').forEach(item => {
                    item.classList.remove('active');
                });
            }

            function updateChatStats(stats) {
                if (stats.total_tokens) {
                    document.getElementById('token-count').textContent =
                        `${stats.total_tokens} tokens`;
                }
                if (stats.total_cost) {
                    document.getElementById('cost-display').textContent =
                        `$${parseFloat(stats.total_cost).toFixed(3)}`;
                }
            }

            async function loadChatMessages(chatId) {
                if (!chatId) return;

                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')
                        ?.getAttribute('content');

                    const response = await fetch(`/admin/ai-chats/${chatId}/messages`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        displayChatMessages(data.messages);

                        // Update chat stats if available
                        if (data.stats) {
                            updateChatStats(data.stats);
                        }
                    } else {
                        console.error('Failed to load chat messages');
                        toastr.error('Failed to load chat messages');
                    }
                } catch (error) {
                    console.error('Error loading chat messages:', error);
                    toastr.error('Network error loading chat messages');
                }
            }

            async function loadPersonas() {
                console.log('loadPersonas()');
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')
                        ?.getAttribute('content');

                    const response = await fetch('/admin/ai-personas', {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        console.log('Personas loaded:', data.data);
                        displayPersonas(data.data);
                    } else {
                        console.error('Failed to load personas');
                        toastr.error('Failed to load personas');
                    }
                } catch (error) {
                    console.error('Error loading personas:', error);
                    toastr.error('Network error loading personas');
                }
            }

            // console.log('loadPersonas();')
            await loadPersonas();

            async function createNewChat() {
                console.log('createNewChat()');
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')
                        ?.getAttribute('content');

                    const response = await fetch('/admin/ai-chats', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            title: 'New Chat Session',
                            persona_id: selectedPersonaId
                        })
                    });

                    if (response.ok) {
                        const data = await response.json();

                        // Add new chat to the chat list
                        const chatList = document.getElementById('chat-list');
                        const newChatHtml = `
                                <div class="chat-item p-2 mb-1 bg-secondary rounded cursor-pointer active" data-chat-id="${data.chat.id}">
                                    <div class="d-flex justify-content-between">
                                        <small class="text-light font-weight-bold">${data.chat.title}</small>
                                        <small class="text-light">just now</small>
                                    </div>
                                    <small class="text-light">New chat session...</small>
                                </div>
                            `;

                        // Remove active class from other chats
                        document.querySelectorAll('.chat-item').forEach(item => {
                            item.classList.remove('active');
                        });

                        // Add new chat at the top
                        chatList.insertAdjacentHTML('afterbegin', newChatHtml);

                        // Set as current chat
                        currentChatId = data.chat.id;
                        document.getElementById('current-chat-name').textContent = data.chat
                            .title;

                        // Clear messages
                        document.getElementById('chat-messages').innerHTML = '';

                        toastr.success('New chat created successfully!');
                    } else {
                        const errorData = await response.json();
                        toastr.error(errorData.message || 'Failed to create new chat');
                    }
                } catch (error) {
                    console.error('Error creating new chat:', error);
                    toastr.error('Network error creating new chat');
                }
            }

            async function clearChat(chatId) {
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')
                        ?.getAttribute('content');

                    const response = await fetch(`/admin/ai-chats/${chatId}/clear`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });

                    if (response.ok) {
                        toastr.success('Chat cleared successfully!');

                        // Reset token count and cost
                        document.getElementById('token-count').textContent = '0 tokens';
                        document.getElementById('cost-display').textContent = '$0.00';
                    } else {
                        const errorData = await response.json();
                        toastr.error(errorData.message || 'Failed to clear chat');
                    }
                } catch (error) {
                    console.error('Error clearing chat:', error);
                    toastr.error('Network error clearing chat');
                }
            }

            async function exportChat(chatId) {
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')
                        ?.getAttribute('content');

                    // Show loading state
                    const exportBtn = document.getElementById('export-chat-btn');
                    const originalHtml = exportBtn.innerHTML;
                    exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                    exportBtn.disabled = true;

                    const response = await fetch(`/admin/ai-chats/${chatId}/export`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });

                    if (response.ok) {
                        // Check if response is JSON or file
                        const contentType = response.headers.get('content-type');

                        if (contentType && contentType.includes('application/json')) {
                            // JSON export
                            const data = await response.json();
                            downloadJsonFile(data, `chat-${chatId}-export.json`);
                        } else {
                            // File download
                            const blob = await response.blob();
                            const filename = response.headers.get('content-disposition')
                                ?.split('filename=')[1]?.replace(/"/g, '') ||
                                `chat-${chatId}-export.txt`;
                            downloadBlob(blob, filename);
                        }

                        toastr.success('Chat exported successfully!');
                    } else {
                        const errorData = await response.json();
                        toastr.error(errorData.message || 'Failed to export chat');
                    }
                } catch (error) {
                    console.error('Error exporting chat:', error);
                    toastr.error('Network error exporting chat');
                } finally {
                    // Restore button state
                    const exportBtn = document.getElementById('export-chat-btn');
                    exportBtn.innerHTML = originalHtml;
                    exportBtn.disabled = false;
                }
            }

            function downloadJsonFile(data, filename) {
                const blob = new Blob([JSON.stringify(data, null, 2)], {
                    type: 'application/json'
                });
                downloadBlob(blob, filename);
            }

            function downloadBlob(blob, filename) {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                if (a?.style?.display) {
                    a.style.display = 'none';
                }
                a.href = url;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
            }

            function displayChatMessages(messages) {
                const chatMessages = document.getElementById('chat-messages');
                chatMessages.innerHTML = '';

                if (!messages || messages.length === 0) {
                    chatMessages.innerHTML =
                        '<div class="text-center text-muted p-3">No messages yet. Start a conversation!</div>';
                    return;
                }

                messages.forEach(message => {
                    addMessage(message.role, message.content, new Date(message.created_at));
                });

                scrollToBottom();
            }

            function displayPersonas(personas) {
                const personaSelect = document.getElementById('ai-persona-select');
                const personaList = document.getElementById('persona-list');

                // Update select dropdown
                personaSelect.innerHTML = '<option value="">Default Assistant</option>';
                if (personas && personas.length > 0) {
                    personas.forEach(persona => {
                        personaSelect.innerHTML +=
                            `<option value="${persona.id}">${persona.name}</option>`;
                    });
                }

                // Update persona list in modal
                if (personaList) {
                    personaList.innerHTML = '';
                    if (personas && personas.length > 0) {
                        personas.forEach(persona => {
                            personaList.innerHTML += `
                                    <div class="list-group-item list-group-item-action bg-secondary text-light persona-item" data-persona-id="${persona.id}">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">${persona.name}</h6>
                                                <small>${persona.description || 'No description'}</small>
                                            </div>
                                            <button class="btn btn-sm btn-outline-danger delete-persona-btn" data-persona-id="${persona.id}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                `;
                        });
                    } else {
                        personaList.innerHTML =
                            '<div class="text-center text-muted p-3">No personas available</div>';
                    }
                }
            }

            function addMessage(role, content, timestamp = null) {
                const chatMessages = document.getElementById('chat-messages');
                const messageTime = timestamp ? new Date(timestamp) : new Date();
                const timeString = messageTime.toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                });

                // Parse Markdown code blocks and render them with highlight.js
                function renderContentWithCodeBlocks(text) {
                    // First, handle code blocks
                    let processedText = text.replace(/```(\w+)?\n([\s\S]*?)```/g, function(match,
                        lang, code) {
                        // Escape HTML inside code block
                        const escapedCode = code.trim().replace(/[<>&"']/g, function(c) {
                            return {
                                '<': '&lt;',
                                '>': '&gt;',
                                '&': '&amp;',
                                '"': '&quot;',
                                "'": '&#39;'
                            } [c];
                        });
                        return `<pre><code class="hljs language-${lang || 'plaintext'}">${escapedCode}</code></pre>`;
                    });

                    // Handle inline code
                    processedText = processedText.replace(/`([^`]+)`/g,
                        '<code class="inline-code">$1</code>');

                    // Convert line breaks to <br> for non-code content
                    // processedText = processedText.replace(/\n/g, '<br>');

                    return processedText;
                }

                let messageHtml = '';

                if (role === 'user') {
                    messageHtml = `
                            <div class="message mb-2">
                                <div class="user-message bg-primary text-white p-2 rounded ml-4">
                                    <small class="d-block mb-1 opacity-75">You • ${timeString}</small>
                                    ${renderContentWithCodeBlocks(content)}
                                </div>
                            </div>
                        `;
                } else if (role === 'assistant') {
                    messageHtml = `
                            <div class="message mb-2">
                                <div class="ai-message bg-secondary text-light p-2 rounded mr-4">
                                    <small class="d-block mb-1 opacity-75">
                                        <i class="fas fa-robot"></i> AI Assistant • ${timeString}
                                    </small>
                                    <div class="message-content">${renderContentWithCodeBlocks(content)}</div>
                                </div>
                            </div>
                        `;
                } else if (role === 'system') {
                    messageHtml = `
                            <div class="message mb-2">
                                <div class="system-message bg-warning text-dark p-2 rounded mx-2">
                                    <small class="d-block mb-1 opacity-75">
                                        <i class="fas fa-exclamation-triangle"></i> System • ${timeString}
                                    </small>
                                    ${renderContentWithCodeBlocks(content)}
                                </div>
                            </div>
                        `;
                }

                chatMessages.insertAdjacentHTML('beforeend', messageHtml);

                // Highlight code blocks after inserting - with a small delay to ensure DOM is updated
                setTimeout(() => {
                    if (window.hljs) {
                        const newMessage = chatMessages.lastElementChild;
                        if (newMessage) {
                            newMessage.querySelectorAll('pre code').forEach(block => {
                                // Remove any existing highlighting
                                block.removeAttribute('data-highlighted');
                                window.hljs.highlightElement(block);
                            });
                        }
                    }
                }, 10);

                scrollToBottom();
            }

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            function scrollToBottom() {
                const chatMessages = document.getElementById('chat-messages');
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            function showTypingIndicator() {
                const chatMessages = document.getElementById('chat-messages');
                const typingHtml = `
                            <div class="message mb-2 typing-indicator">
                                <div class="ai-message bg-secondary text-light p-2 rounded mr-4">
                                    <small class="d-block mb-1 opacity-75">
                                        <i class="fas fa-robot"></i> AI Assistant • typing...
                                    </small>
                                    <div class="typing-animation">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                </div>
                            </div>
                        `;
                chatMessages.insertAdjacentHTML('beforeend', typingHtml);
                scrollToBottom();
            }

            async function loadChats() {
                console.log('loadChats()');
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')
                        ?.getAttribute('content');

                    const response = await fetch('/admin/ai-chats', {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        console.log('Chats loaded:', data);
                        displayChats(data.data || data);
                    } else {
                        console.error('Failed to load chats');
                        toastr.error('Failed to load chats');
                    }
                } catch (error) {
                    console.error('Error loading chats:', error);
                    toastr.error('Network error loading chats');
                }
            }

            function displayChats(chats) {
                console.log('displayChats()', chats);
                const chatList = document.getElementById('chat-list');

                if (!chats || chats.length === 0) {
                    chatList.innerHTML =
                        '<div class="text-center text-muted p-3">No chat sessions yet. Start a new chat!</div>';
                    return;
                }

                chatList.innerHTML = '';
                chats.forEach(chat => {
                    const timeAgo = getTimeAgo(new Date(chat.updated_at));
                    const preview = chat.last_message_preview || 'New chat session...';

                    const chatHtml = `
                                    <div class="chat-item p-2 mb-1 bg-secondary rounded cursor-pointer" data-chat-id="${chat.id}">
                                        <div class="d-flex justify-content-between">
                                            <small class="text-light font-weight-bold">${escapeHtml(chat.session_name || 'Chat Session')}</small>
                                            <small class="text-light">${timeAgo}</small>
                                        </div>
                                        <small class="text-light">${escapeHtml(preview.substring(0, 40))}${preview.length > 40 ? '...' : ''}</small>
                                        ${chat.persona ? `<div><span class="badge badge-info badge-sm mt-1">${escapeHtml(chat.persona.name)}</span></div>` : ''}
                                    </div>
                                `;

                    chatList.insertAdjacentHTML('beforeend', chatHtml);
                });

                // Auto-select the first chat if none is selected
                if (!currentChatId && chats.length > 0) {
                    const firstChat = chats[0];
                    currentChatId = firstChat.id;

                    // Mark first chat as active
                    const firstChatElement = document.querySelector(
                        `[data-chat-id="${firstChat.id}"]`);
                    if (firstChatElement) {
                        firstChatElement.classList.add('active');
                    }

                    // Update current chat name
                    document.getElementById('current-chat-name').textContent = firstChat
                        .session_name || 'Chat Session';

                    // Load messages for first chat
                    loadChatMessages(firstChat.id);
                }
            }

            function getTimeAgo(date) {
                const now = new Date();
                const diffInSeconds = Math.floor((now - date) / 1000);

                if (diffInSeconds < 60) {
                    return 'just now';
                } else if (diffInSeconds < 3600) {
                    const minutes = Math.floor(diffInSeconds / 60);
                    return `${minutes}m ago`;
                } else if (diffInSeconds < 86400) {
                    const hours = Math.floor(diffInSeconds / 3600);
                    return `${hours}h ago`;
                } else {
                    const days = Math.floor(diffInSeconds / 86400);
                    return `${days}d ago`;
                }
            }

            // Load chats when the AI functionality is initialized
            await loadChats();
        });
    });
</script>
<script>
    // Resizable sidebar
    const sidebar = document.getElementById("controlSidebar");
    const handle = sidebar.querySelector(".resize-handle");

    let isResizing = false;

    // Helper functions for cookies
    function setCookie(name, value, days = 365) {
        const expires = new Date(Date.now() + days * 864e5).toUTCString();
        document.cookie = name + '=' + encodeURIComponent(value) + '; expires=' + expires + '; path=/';
    }

    function getCookie(name) {
        return document.cookie.split('; ').reduce((r, v) => {
            const parts = v.split('=');
            return parts[0] === name ? decodeURIComponent(parts[1]) : r
        }, '');
    }

    // Load width from cookie
    const savedWidth = parseInt(getCookie('sidebarWidth'), 10);
    if (savedWidth && savedWidth >= 10 && savedWidth <= 1500) {
        sidebar.style.width = savedWidth + "px";
    }

    handle.addEventListener("mousedown", function(e) {
        isResizing = true;
        document.body.style.userSelect = "none"; // prevent text selection
    });

    document.addEventListener("mousemove", function(e) {
        if (!isResizing) return;

        const newWidth = window.innerWidth - e.clientX; // distance from right edge
        if (newWidth > 10) { // respect min/max
            sidebar.style.width = newWidth + "px";
            setCookie('sidebarWidth', newWidth);
        }
    });

    document.addEventListener("mouseup", function() {
        isResizing = false;
        document.body.style.userSelect = "";
    });

    // Touch events for mobile resizing
    handle.addEventListener("touchstart", function(e) {
        console.log('touchstart sidebar handle');
        isResizing = true;
        document.body.style.userSelect = "none";
        e.preventDefault();
    }, { passive: false });

    document.addEventListener("touchmove", function(e) {
        if (!isResizing) return;
        const touch = e.touches[0];
        const newWidth = window.innerWidth - touch.clientX;
        if (newWidth > 10) {
            sidebar.style.width = newWidth + "px";
            setCookie('sidebarWidth', newWidth);
        }
    }, { passive: false });

    document.addEventListener("touchend", function() {
        console.log('touchend sidebar handle');
        isResizing = false;
        document.body.style.userSelect = "";
    }, { passive: false });
</script>

# MC Dev Panel

## Project Status  
*(original content from `README_MC_DEV_PANEL.txt`)*

---

## Fundamental Rules (Mircea’s Laws)  
*(original content from `Mirceas_laws.txt`)*

---

## Current Progress

### 1. Centralized Router
- `index.php` handles both public and protected tasks using a whitelist/blacklist mechanism.
- Dedicated blocks for `login`, `do_login`, `panel`, `ai_assistant`, and `logout`.

### 2. Logging & Debugging
- `Logger` class located in `includes/Logger.php` with `info()`, `error()`, and `debug()` methods.
- Log file defined by `LOG_FILE` in `includes/paths.php`.
- `logs/` directory created with `www-data:www-data` ownership.

### 3. AI Assistant (Nelu)
- Controller `tasks/ai_assistant.php`:
  - Calls OpenAI API (GPT-4 model) via `call_openai_api()`.
  - Accepts natural language prompts and enforces responses prefixed with `dir_create:` or `file_write:`.
  - Stores the suggested command in session and auto-executes it (including multi-line command blocks).
- Skin-based template in `skins/<skin>/ai_assistant.php`:
  - Includes `head.php`, `header.php`, `footer.php`.
  - Displays the generated command and execution result (`$aiExecResult`) in a styled container.

### 4. Server Command Execution
- Script `tasks/ai_assistant/exec_command.php` supports:
  1. **Directory creation**: `dir_create:/path` (relative or absolute)
  2. **File writing**:
     - `file_write:/path:BASE64` — decodes and writes base64 content.
     - `file_write:/path, RAW_CONTENT` — writes raw plain text/HTML/PHP content.
  3. **Advanced operations**: `list_dir`, `read_file`, `delete_file`, `move`, `copy`
- PHP warning suppression (e.g. `@mkdir()`, `@file_put_contents()`)

### 5. Skinning & Layout
- Templates in `skins/<skin>/` (e.g. `dark_navy`) include shared styling and layout components.
- The AI Assistant form follows the same grid layout and control structure as panel pages.

---

## Installation Instructions

1. Download the project into `/var/www/panel.mcdevpanel.xyz`  
2. Configure `includes/paths.php` with `DEFAULT_SKIN` and `OPENAI_CONFIG`  
3. Create the log directory:
   ```bash
   sudo mkdir /var/www/panel.mcdevpanel.xyz/logs
   sudo chown www-data:www-data /var/www/panel.mcdevpanel.xyz/logs
   chmod 755 /var/www/panel.mcdevpanel.xyz/logs

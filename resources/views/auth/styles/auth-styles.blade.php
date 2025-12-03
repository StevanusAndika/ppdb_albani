<style>
.password-input-wrapper {
    position: relative;
}

.password-toggle-btn {
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px 8px;
    border-radius: 4px;
    transition: all 0.2s ease;
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    z-index: 10;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
}

.password-toggle-btn:hover {
    background-color: rgba(0, 0, 0, 0.1);
}

.password-toggle-btn:focus {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

/* Strength bar colors */
.strength-bar.weak { background-color: #ef4444 !important; }
.strength-bar.medium { background-color: #f59e0b !important; }
.strength-bar.strong { background-color: #10b981 !important; }
.strength-bar.very-strong { background-color: #047857 !important; }

/* Requirement styles */
.requirement.met i {
    color: #10b981 !important;
}

.requirement.met span {
    color: #10b981 !important;
}

.requirement.not-met i {
    color: #ef4444 !important;
}

.requirement.not-met span {
    color: #ef4444 !important;
}

/* Button styles */
.btn-primary {
    background: #002f2d
}

.btn-primary:hover:not(:disabled) {
    background: #002f2d;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.input-field {
    padding-right: 45px !important;
}
</style>

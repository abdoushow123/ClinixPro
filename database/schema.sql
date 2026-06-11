-- =====================================================
-- CLINIXPRO - Hospital Management System
-- PostgreSQL Database Schema
-- =====================================================

-- Enable UUID extension
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- =====================================================
-- ROLES TABLE
-- =====================================================
CREATE TABLE roles (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    permissions JSONB,
    can_approve_roles INTEGER[] DEFAULT ARRAY[]::INTEGER[],
    requires_approval BOOLEAN DEFAULT true,
    auto_approve BOOLEAN DEFAULT false,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default roles with approval configuration
-- can_approve_roles: array of role IDs this role can approve
-- requires_approval: whether this role needs approval before activation
-- auto_approve: whether this role is auto-approved (system roles)
INSERT INTO roles (name, description, permissions, can_approve_roles, requires_approval, auto_approve) VALUES
('administrator', 'Full system access', '{"all": true}', ARRAY[1,2,3,4,5,6], true, false),
('doctor', 'Medical records and patient management', '{"patients": ["read", "write"], "medical_records": ["read", "write"], "prescriptions": ["write"]}', ARRAY[2,3], true, false),
('nurse', 'Patient care and basic records', '{"patients": ["read"], "medical_records": ["read", "write"]}', ARRAY[]::INTEGER[], true, false),
('receptionist', 'Patient registration and appointments', '{"patients": ["read", "write"], "appointments": ["read", "write"]}', ARRAY[]::INTEGER[], true, false),
('pharmacist', 'Pharmacy and prescriptions', '{"prescriptions": ["read", "write"], "inventory": ["read", "write"]}', ARRAY[5], true, false),
('laboratory', 'Lab tests and results', '{"lab_tests": ["read", "write"], "results": ["read", "write"]}', ARRAY[6], true, false);

-- =====================================================
-- USERS TABLE
-- =====================================================
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    uuid UUID DEFAULT uuid_generate_v4() UNIQUE,
    role_id INTEGER REFERENCES roles(id) ON DELETE SET NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    username VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    is_active BOOLEAN DEFAULT true,
    email_verified BOOLEAN DEFAULT false,
    registration_status VARCHAR(20) DEFAULT 'approved' CHECK (registration_status IN ('pending', 'approved', 'rejected')),
    registration_notes TEXT,
    approved_by INTEGER REFERENCES users(id),
    approved_by_role_id INTEGER REFERENCES roles(id),
    approved_at TIMESTAMP,
    assigned_doctor_id INTEGER REFERENCES users(id),
    cv_path VARCHAR(255),
    failed_login_attempts INTEGER DEFAULT 0,
    locked_until TIMESTAMP NULL,
    last_login_at TIMESTAMP NULL,
    last_login_ip VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indexes for users
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_username ON users(username);
CREATE INDEX idx_users_role_id ON users(role_id);
CREATE INDEX idx_users_is_active ON users(is_active);

-- =====================================================
-- LOGIN LOGS TABLE
-- =====================================================
CREATE TABLE login_logs (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT,
    status VARCHAR(20) NOT NULL, -- success, failed, blocked
    failure_reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_login_logs_user_id ON login_logs(user_id);
CREATE INDEX idx_login_logs_created_at ON login_logs(created_at);

-- =====================================================
-- ACTIVITY LOGS TABLE
-- =====================================================
CREATE TABLE activity_logs (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(50),
    entity_id INTEGER,
    details JSONB,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_activity_logs_user_id ON activity_logs(user_id);
CREATE INDEX idx_activity_logs_action ON activity_logs(action);
CREATE INDEX idx_activity_logs_created_at ON activity_logs(created_at);

-- =====================================================
-- PATIENTS TABLE
-- =====================================================
CREATE TABLE patients (
    id SERIAL PRIMARY KEY,
    uuid UUID DEFAULT uuid_generate_v4() UNIQUE,
    patient_id VARCHAR(20) NOT NULL UNIQUE,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender VARCHAR(20) NOT NULL CHECK (gender IN ('male', 'female', 'other')),
    blood_type VARCHAR(5),
    email VARCHAR(255),
    phone VARCHAR(20) NOT NULL,
    address TEXT,
    city VARCHAR(100),
    state VARCHAR(100),
    postal_code VARCHAR(20),
    country VARCHAR(100) DEFAULT 'USA',
    emergency_contact_name VARCHAR(100),
    emergency_contact_phone VARCHAR(20),
    emergency_contact_relationship VARCHAR(50),
    insurance_provider VARCHAR(100),
    insurance_policy_number VARCHAR(50),
    allergies TEXT,
    chronic_conditions TEXT,
    notes TEXT,
    created_by INTEGER REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indexes for patients
CREATE INDEX idx_patients_patient_id ON patients(patient_id);
CREATE INDEX idx_patients_name ON patients(last_name, first_name);
CREATE INDEX idx_patients_dob ON patients(date_of_birth);
CREATE INDEX idx_patients_phone ON patients(phone);

-- =====================================================
-- DOCTORS TABLE
-- =====================================================
CREATE TABLE doctors (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
    specialty VARCHAR(100) NOT NULL,
    license_number VARCHAR(50) UNIQUE,
    qualification TEXT,
    experience_years INTEGER,
    consultation_fee DECIMAL(10, 2),
    is_available BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_doctors_user_id ON doctors(user_id);
CREATE INDEX idx_doctors_specialty ON doctors(specialty);

-- =====================================================
-- ROOMS TABLE
-- =====================================================
CREATE TABLE rooms (
    id SERIAL PRIMARY KEY,
    room_number VARCHAR(20) NOT NULL UNIQUE,
    room_type VARCHAR(50) NOT NULL CHECK (room_type IN ('general', 'private', 'icu', 'emergency', 'operating', 'laboratory', 'pharmacy')),
    floor INTEGER,
    bed_capacity INTEGER NOT NULL DEFAULT 1,
    current_occupancy INTEGER DEFAULT 0,
    hourly_rate DECIMAL(10, 2),
    is_active BOOLEAN DEFAULT true,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_rooms_room_number ON rooms(room_number);
CREATE INDEX idx_rooms_room_type ON rooms(room_type);
CREATE INDEX idx_rooms_is_active ON rooms(is_active);

-- =====================================================
-- APPOINTMENTS TABLE
-- =====================================================
CREATE TABLE appointments (
    id SERIAL PRIMARY KEY,
    uuid UUID DEFAULT uuid_generate_v4() UNIQUE,
    patient_id INTEGER NOT NULL REFERENCES patients(id) ON DELETE CASCADE,
    doctor_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    duration_minutes INTEGER DEFAULT 30,
    status VARCHAR(20) DEFAULT 'pending' CHECK (status IN ('pending', 'confirmed', 'completed', 'cancelled', 'no_show')),
    reason TEXT,
    notes TEXT,
    created_by INTEGER REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_appointments_patient_id ON appointments(patient_id);
CREATE INDEX idx_appointments_doctor_id ON appointments(doctor_id);
CREATE INDEX idx_appointments_date ON appointments(appointment_date);
CREATE INDEX idx_appointments_status ON appointments(status);

-- =====================================================
-- HOSPITALIZATIONS TABLE
-- =====================================================
CREATE TABLE hospitalizations (
    id SERIAL PRIMARY KEY,
    uuid UUID DEFAULT uuid_generate_v4() UNIQUE,
    patient_id INTEGER NOT NULL REFERENCES patients(id) ON DELETE CASCADE,
    room_id INTEGER REFERENCES rooms(id) ON DELETE SET NULL,
    admission_date DATE NOT NULL,
    admission_time TIME NOT NULL,
    discharge_date DATE,
    discharge_time TIME,
    status VARCHAR(20) DEFAULT 'admitted' CHECK (status IN ('admitted', 'discharged', 'transferred')),
    admission_reason TEXT,
    discharge_summary TEXT,
    attending_doctor_id INTEGER REFERENCES users(id),
    created_by INTEGER REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_hospitalizations_patient_id ON hospitalizations(patient_id);
CREATE INDEX idx_hospitalizations_room_id ON hospitalizations(room_id);
CREATE INDEX idx_hospitalizations_status ON hospitalizations(status);
CREATE INDEX idx_hospitalizations_admission_date ON hospitalizations(admission_date);

-- =====================================================
-- MEDICAL RECORDS TABLE
-- =====================================================
CREATE TABLE medical_records (
    id SERIAL PRIMARY KEY,
    uuid UUID DEFAULT uuid_generate_v4() UNIQUE,
    patient_id INTEGER NOT NULL REFERENCES patients(id) ON DELETE CASCADE,
    doctor_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
    visit_date DATE NOT NULL,
    visit_type VARCHAR(50) NOT NULL,
    chief_complaint TEXT,
    diagnosis TEXT NOT NULL,
    symptoms TEXT,
    treatment_plan TEXT,
    notes TEXT,
    is_confidential BOOLEAN DEFAULT false,
    created_by INTEGER REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_medical_records_patient_id ON medical_records(patient_id);
CREATE INDEX idx_medical_records_doctor_id ON medical_records(doctor_id);
CREATE INDEX idx_medical_records_visit_date ON medical_records(visit_date);
CREATE INDEX idx_medical_records_is_confidential ON medical_records(is_confidential);

-- =====================================================
-- PRESCRIPTIONS TABLE
-- =====================================================
CREATE TABLE prescriptions (
    id SERIAL PRIMARY KEY,
    uuid UUID DEFAULT uuid_generate_v4() UNIQUE,
    patient_id INTEGER NOT NULL REFERENCES patients(id) ON DELETE CASCADE,
    doctor_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
    medical_record_id INTEGER REFERENCES medical_records(id) ON DELETE SET NULL,
    medication_name VARCHAR(200) NOT NULL,
    dosage VARCHAR(100) NOT NULL,
    frequency VARCHAR(100) NOT NULL,
    duration VARCHAR(100) NOT NULL,
    instructions TEXT,
    prescription_date DATE NOT NULL,
    status VARCHAR(20) DEFAULT 'pending' CHECK (status IN ('pending', 'dispensed', 'cancelled')),
    dispensed_by INTEGER REFERENCES users(id),
    dispensed_date DATE,
    notes TEXT,
    created_by INTEGER REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_prescriptions_patient_id ON prescriptions(patient_id);
CREATE INDEX idx_prescriptions_doctor_id ON prescriptions(doctor_id);
CREATE INDEX idx_prescriptions_status ON prescriptions(status);
CREATE INDEX idx_prescriptions_date ON prescriptions(prescription_date);

-- =====================================================
-- LABORATORY TESTS TABLE
-- =====================================================
CREATE TABLE laboratory_tests (
    id SERIAL PRIMARY KEY,
    uuid UUID DEFAULT uuid_generate_v4() UNIQUE,
    test_code VARCHAR(20) NOT NULL UNIQUE,
    test_name VARCHAR(200) NOT NULL,
    category VARCHAR(100),
    description TEXT,
    normal_range TEXT,
    sample_type VARCHAR(50),
    price DECIMAL(10, 2),
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_laboratory_tests_test_code ON laboratory_tests(test_code);
CREATE INDEX idx_laboratory_tests_category ON laboratory_tests(category);

-- =====================================================
-- LAB REQUESTS TABLE
-- =====================================================
CREATE TABLE lab_requests (
    id SERIAL PRIMARY KEY,
    uuid UUID DEFAULT uuid_generate_v4() UNIQUE,
    patient_id INTEGER NOT NULL REFERENCES patients(id) ON DELETE CASCADE,
    doctor_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
    test_id INTEGER NOT NULL REFERENCES laboratory_tests(id) ON DELETE RESTRICT,
    request_date DATE NOT NULL,
    request_time TIME NOT NULL,
    status VARCHAR(20) DEFAULT 'pending' CHECK (status IN ('pending', 'in_progress', 'completed', 'cancelled')),
    priority VARCHAR(20) DEFAULT 'normal' CHECK (priority IN ('low', 'normal', 'high', 'urgent')),
    clinical_notes TEXT,
    created_by INTEGER REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_lab_requests_patient_id ON lab_requests(patient_id);
CREATE INDEX idx_lab_requests_doctor_id ON lab_requests(doctor_id);
CREATE INDEX idx_lab_requests_status ON lab_requests(status);
CREATE INDEX idx_lab_requests_date ON lab_requests(request_date);

-- =====================================================
-- LAB RESULTS TABLE
-- =====================================================
CREATE TABLE lab_results (
    id SERIAL PRIMARY KEY,
    lab_request_id INTEGER NOT NULL REFERENCES lab_requests(id) ON DELETE CASCADE,
    result_date DATE NOT NULL,
    result_time TIME NOT NULL,
    results TEXT NOT NULL,
    interpretation TEXT,
    is_abnormal BOOLEAN DEFAULT false,
    technician_id INTEGER REFERENCES users(id),
    verified_by INTEGER REFERENCES users(id),
    verified_at TIMESTAMP,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_lab_results_lab_request_id ON lab_results(lab_request_id);
CREATE INDEX idx_lab_results_result_date ON lab_results(result_date);

-- =====================================================
-- MEDICATION INVENTORY TABLE
-- =====================================================
CREATE TABLE medication_inventory (
    id SERIAL PRIMARY KEY,
    medication_name VARCHAR(200) NOT NULL,
    generic_name VARCHAR(200),
    manufacturer VARCHAR(100),
    category VARCHAR(100),
    dosage_form VARCHAR(50),
    strength VARCHAR(50),
    current_stock INTEGER NOT NULL DEFAULT 0,
    minimum_stock_level INTEGER DEFAULT 10,
    unit_price DECIMAL(10, 2),
    expiry_date DATE,
    batch_number VARCHAR(50),
    supplier VARCHAR(100),
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_medication_inventory_name ON medication_inventory(medication_name);
CREATE INDEX idx_medication_inventory_category ON medication_inventory(category);
CREATE INDEX idx_medication_inventory_expiry ON medication_inventory(expiry_date);

-- =====================================================
-- INVENTORY TRANSACTIONS TABLE
-- =====================================================
CREATE TABLE inventory_transactions (
    id SERIAL PRIMARY KEY,
    medication_id INTEGER NOT NULL REFERENCES medication_inventory(id) ON DELETE CASCADE,
    transaction_type VARCHAR(20) NOT NULL CHECK (transaction_type IN ('stock_in', 'stock_out', 'adjustment', 'expiry')),
    quantity INTEGER NOT NULL,
    reference_type VARCHAR(50),
    reference_id INTEGER,
    notes TEXT,
    performed_by INTEGER REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_inventory_transactions_medication_id ON inventory_transactions(medication_id);
CREATE INDEX idx_inventory_transactions_type ON inventory_transactions(transaction_type);
CREATE INDEX idx_inventory_transactions_created_at ON inventory_transactions(created_at);

-- =====================================================
-- INVOICES TABLE
-- =====================================================
CREATE TABLE invoices (
    id SERIAL PRIMARY KEY,
    uuid UUID DEFAULT uuid_generate_v4() UNIQUE,
    patient_id INTEGER NOT NULL REFERENCES patients(id) ON DELETE CASCADE,
    invoice_number VARCHAR(30) NOT NULL UNIQUE,
    invoice_date DATE NOT NULL,
    due_date DATE NOT NULL,
    subtotal DECIMAL(12, 2) NOT NULL,
    tax_amount DECIMAL(12, 2) DEFAULT 0,
    discount_amount DECIMAL(12, 2) DEFAULT 0,
    total_amount DECIMAL(12, 2) NOT NULL,
    paid_amount DECIMAL(12, 2) DEFAULT 0,
    balance_amount DECIMAL(12, 2) NOT NULL,
    status VARCHAR(20) DEFAULT 'pending' CHECK (status IN ('pending', 'partial', 'paid', 'overdue', 'cancelled')),
    notes TEXT,
    created_by INTEGER REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_invoices_patient_id ON invoices(patient_id);
CREATE INDEX idx_invoices_invoice_number ON invoices(invoice_number);
CREATE INDEX idx_invoices_status ON invoices(status);
CREATE INDEX idx_invoices_due_date ON invoices(due_date);

-- =====================================================
-- INVOICE ITEMS TABLE
-- =====================================================
CREATE TABLE invoice_items (
    id SERIAL PRIMARY KEY,
    invoice_id INTEGER NOT NULL REFERENCES invoices(id) ON DELETE CASCADE,
    item_type VARCHAR(50) NOT NULL,
    item_id INTEGER,
    description TEXT NOT NULL,
    quantity INTEGER NOT NULL DEFAULT 1,
    unit_price DECIMAL(10, 2) NOT NULL,
    total_price DECIMAL(12, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_invoice_items_invoice_id ON invoice_items(invoice_id);

-- =====================================================
-- PAYMENTS TABLE
-- =====================================================
CREATE TABLE payments (
    id SERIAL PRIMARY KEY,
    uuid UUID DEFAULT uuid_generate_v4() UNIQUE,
    invoice_id INTEGER NOT NULL REFERENCES invoices(id) ON DELETE CASCADE,
    payment_date DATE NOT NULL,
    payment_method VARCHAR(50) NOT NULL CHECK (payment_method IN ('cash', 'card', 'insurance', 'bank_transfer')),
    amount DECIMAL(12, 2) NOT NULL,
    reference_number VARCHAR(100),
    notes TEXT,
    received_by INTEGER REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_payments_invoice_id ON payments(invoice_id);
CREATE INDEX idx_payments_payment_date ON payments(payment_date);
CREATE INDEX idx_payments_payment_method ON payments(payment_method);

-- =====================================================
-- INSURANCE CLAIMS TABLE
-- =====================================================
CREATE TABLE insurance_claims (
    id SERIAL PRIMARY KEY,
    uuid UUID DEFAULT uuid_generate_v4() UNIQUE,
    patient_id INTEGER NOT NULL REFERENCES patients(id) ON DELETE CASCADE,
    invoice_id INTEGER NOT NULL REFERENCES invoices(id) ON DELETE CASCADE,
    insurance_provider VARCHAR(100) NOT NULL,
    policy_number VARCHAR(50) NOT NULL,
    claim_number VARCHAR(50) UNIQUE,
    claim_date DATE NOT NULL,
    claimed_amount DECIMAL(12, 2) NOT NULL,
    approved_amount DECIMAL(12, 2),
    status VARCHAR(20) DEFAULT 'submitted' CHECK (status IN ('submitted', 'under_review', 'approved', 'partially_approved', 'denied')),
 denial_reason TEXT,
    submitted_by INTEGER REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_insurance_claims_patient_id ON insurance_claims(patient_id);
CREATE INDEX idx_insurance_claims_invoice_id ON insurance_claims(invoice_id);
CREATE INDEX idx_insurance_claims_status ON insurance_claims(status);

-- =====================================================
-- CSRF TOKENS TABLE
-- =====================================================
CREATE TABLE csrf_tokens (
    id SERIAL PRIMARY KEY,
    token VARCHAR(64) NOT NULL UNIQUE,
    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_csrf_tokens_token ON csrf_tokens(token);
CREATE INDEX idx_csrf_tokens_expires ON csrf_tokens(expires_at);

-- =====================================================
-- PASSWORD RESET TOKENS TABLE
-- =====================================================
CREATE TABLE password_reset_tokens (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    token VARCHAR(64) NOT NULL UNIQUE,
    expires_at TIMESTAMP NOT NULL,
    used BOOLEAN DEFAULT false,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_password_reset_tokens_token ON password_reset_tokens(token);
CREATE INDEX idx_password_reset_tokens_user_id ON password_reset_tokens(user_id);
CREATE INDEX idx_password_reset_tokens_expires ON password_reset_tokens(expires_at);

-- =====================================================
-- TRIGGERS FOR UPDATED_AT
-- =====================================================
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

-- Apply trigger to all tables with updated_at
CREATE TRIGGER update_users_updated_at BEFORE UPDATE ON users
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_patients_updated_at BEFORE UPDATE ON patients
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_doctors_updated_at BEFORE UPDATE ON doctors
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_rooms_updated_at BEFORE UPDATE ON rooms
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_appointments_updated_at BEFORE UPDATE ON appointments
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_hospitalizations_updated_at BEFORE UPDATE ON hospitalizations
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_medical_records_updated_at BEFORE UPDATE ON medical_records
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_prescriptions_updated_at BEFORE UPDATE ON prescriptions
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_laboratory_tests_updated_at BEFORE UPDATE ON laboratory_tests
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_lab_requests_updated_at BEFORE UPDATE ON lab_requests
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_lab_results_updated_at BEFORE UPDATE ON lab_results
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_medication_inventory_updated_at BEFORE UPDATE ON medication_inventory
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_invoices_updated_at BEFORE UPDATE ON invoices
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_insurance_claims_updated_at BEFORE UPDATE ON insurance_claims
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- =====================================================
-- SAMPLE DATA INSERTION
-- =====================================================

-- Insert default administrator user (password: Admin@123)
INSERT INTO users (role_id, email, username, password_hash, first_name, last_name, is_active, email_verified)
VALUES (1, 'admin@clinixpro.com', 'admin', '$argon2id$v=19$m=65536,t=4,p=1$TkhmNS5ndDhXbEFqMXZkeQ$INKhGkYzryaIZNW9lL59CxOufKa1OITVKEK/DAKSGgU', 'System', 'Administrator', true, true);

-- =====================================================
-- NOTIFICATIONS TABLE
-- =====================================================
CREATE TABLE notifications (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type VARCHAR(50) DEFAULT 'info' CHECK (type IN ('info', 'success', 'warning', 'danger')),
    reference_url VARCHAR(255),
    is_read BOOLEAN DEFAULT false,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_notifications_user_id ON notifications(user_id);
CREATE INDEX idx_notifications_is_read ON notifications(is_read);

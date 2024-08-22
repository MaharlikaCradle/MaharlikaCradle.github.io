import pyodbc
import hashlib

# Connect to SQL Server database
conn = pyodbc.connect('DRIVER={SQL Server};'
                      'SERVER=DESKTOP-L4C2A99\SQLEXPRESS;'
                      'DATABASE=MaharlikasCradleDB;'
                      'Trusted_Connection=yes;')

cursor = conn.cursor()

# Function to register a new user
def register_user(email, password):
    # Hash the password before storing
    hashed_password = hashlib.sha256(password.encode()).hexdigest()

    try:
        cursor.execute('''
            INSERT INTO Users (Email, PasswordHash)
            VALUES (?, ?)
        ''', (email, hashed_password))
        conn.commit()
        return True
    except pyodbc.IntegrityError:
        # Handle duplicate email (unique constraint)
        return False

# Function to reset password for a user
def reset_password(email, new_password):
    # Hash the new password before updating
    hashed_password = hashlib.sha256(new_password.encode()).hexdigest()

    try:
        cursor.execute('''
            UPDATE Users
            SET PasswordHash = ?
            WHERE Email = ?
        ''', (hashed_password, email))
        conn.commit()
        return True
    except pyodbc.Error:
        return False

# Close connection when done
def close_connection():
    conn.close()

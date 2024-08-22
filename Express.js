const express = require('express');
const bodyParser = require('body-parser');
const mssql = require('mssql');
const app = express();
const port = 3000;

app.use(bodyParser.urlencoded({ extended: false }));
app.use(bodyParser.json());

// Database configuration
const config = {
  user: 'your_username',
  password: 'your_password',
  server: 'DESKTOP-L4C2A99\SQLEXPRESS',
  database: 'MaharlikasCradleDB',
  options: {
    trustedconnection: true,
    enableArithAbort: true,
    encrypt: false,
  },
};

// Connect to SQL Server
mssql.connect(config, err => {
  if (err) console.log(err);
  else console.log('Connected to MSSQL');
});

// Define routes for handling requests (e.g., login, register, etc.)

app.listen(port, () => {
  console.log(`Server is running on http://localhost:${port}`);
});

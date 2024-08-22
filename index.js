const express = require('express');
const nodemailer = require('nodemailer');
const randomstring = require('randomstring');

const app = express();
const port = 3000;

// Initialize Nodemailer transporter
const transporter = nodemailer.createTransport({
  service: 'Gmail',
  auth: {
    user: 'your-email@gmail.com',
    pass: 'your-email-password'
  }
});

// Generate OTP
function generateOTP() {
  return randomstring.generate({
    length: 6,
    charset: 'numeric'
  });
}

// Store OTPs for verification
const otpMap = new Map();

// Send OTP via email
app.get('/send-otp', (req, res) => {
  const email = req.query.email;
  const otp = generateOTP();

  // Store OTP in the map
  otpMap.set(email, otp);

  const mailOptions = {
    from: 'your-email@gmail.com',
    to: email,
    subject: 'Email Verification OTP',
    text: Your OTP is: ${otp}
  };

  transporter.sendMail(mailOptions, (error, info) => {
    if (error) {
      console.log(error);
      res.status(500).send('Failed to send OTP');
    } else {
      console.log('Email sent: ' + info.response);
      res.status(200).send('OTP sent successfully');
    }
  });
});

// Verify OTP
app.get('/verify-otp', (req, res) => {
  const email = req.query.email;
  const otp = req.query.otp;

  if (otpMap.has(email) && otpMap.get(email) === otp) {
    // OTP is valid
    otpMap.delete(email);
    res.status(200).send('OTP verified successfully');
  } else {
    // OTP is invalid
    res.status(400).send('Invalid OTP');
  }
});

app.listen(port, () => {
  console.log(Server running at http://localhost:${port});
});
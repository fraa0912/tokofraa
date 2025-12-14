require("dotenv").config();
const express = require("express");
const cors = require("cors");
const nodemailer = require("nodemailer");

const app = express();
app.use(cors());
app.use(express.json());

// transporter Gmail
const transporter = nodemailer.createTransport({
  host: "smtp.gmail.com",
  port: 587,
  secure: false, // pakai STARTTLS, bukan SSL langsung
  auth: {
    user: process.env.EMAIL_USER,
    pass: process.env.EMAIL_PASS, // app password
  },
});

// endpoint kirim kode verif
app.post("/send-code", async (req, res) => {
  try {
    const { email } = req.body;
    if (!email) {
      return res.status(400).json({ message: "Email wajib diisi" });
    }

    // buat kode 6 digit
    const code = Math.floor(100000 + Math.random() * 900000).toString();

    const mailOptions = {
      from: `"TokoKu" <${process.env.EMAIL_USER}>`,
      to: email,
      subject: "Kode verifikasi TokoKu",
      text: `Kode verifikasi kamu: ${code} (berlaku 10 menit).`,
      html: `
        <p>Halo,</p>
        <p>Ini kode verifikasi untuk akun TokoKu kamu:</p>
        <h2>${code}</h2>
        <p>Masukkan kode ini di halaman TokoKu untuk melanjutkan reset password.</p>
      `,
    };

    await transporter.sendMail(mailOptions); // kirim email [web:173][web:183]

    // untuk demo: kirim balik code ke frontend
    res.json({ message: "Kode verifikasi dikirim ke email.", code });
  } catch (err) {
    console.error(err);
    res.status(500).json({ message: "Gagal mengirim email." });
  }
});

const port = process.env.PORT || 4000;
app.listen(port, () => {
  console.log("Server berjalan di port", port);
});

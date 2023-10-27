import Head from 'next/head';
import styles from '../styles/Home.module.css';
import Link from 'next/link';
import Nav from '../components/Nav';
import 'bootstrap/dist/css/bootstrap.css';
import Header from '../components/Header';
import Apropos from '../components/Apropos';
import Realisation from '../components/Realisation';
import Contact from '../components/Contact';
import Footer from '../components/Footer';
import Script from 'next/script';
import Cookies from 'js-cookie';

export default function Home() {
  return (
    <>

      <Link rel="preconnect" href="https://fonts.googleapis.com" />
      <Link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="true" />
      <Link href="https://fonts.googleapis.com/css2?family=Caveat&display=swap" rel="stylesheet" />
      <Link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet" />
      <div className={styles.html}>
        <Nav />
        <Header />
        <Apropos />
        <Realisation />
      </div>
      <Contact />
      <Footer />
      <Script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" />
    </>
  );
}

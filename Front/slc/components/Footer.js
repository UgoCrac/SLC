import React from 'react';
import 'bootstrap/dist/css/bootstrap.css';
import styles from '../styles/Footer.module.css'
import Link from 'next/link';

function Footer() {
    return (
        <footer className={`${styles.footer} d-flex justify-content-between`}>
            <img src="img/logo2.jpg" alt="logo" className='m-2' style={{ width: 100 }} />
            <ul className='d-flex list-unstyled align-items-center m-0'>
                <li className='mx-5'><a href="#" className={`${styles.montserrat} text-decoration-none text-reset`}>Mentions légales</a></li>
                <li className='mx-5'><a href="#" className={`${styles.montserrat} text-decoration-none text-reset`}>Politique de confidentialité</a></li>
                <li className='mx-5'>
                    <Link href='/Login' className={`${styles.montserrat} text-decoration-none text-reset`}>
                        Administration
                    </Link>
                </li>
            </ul>
            <div className='d-flex align-items-center'>
                <a href="#" className='me-5'><img src="/img/facebook.png" alt="" style={{ width: 35 }} /></a>
            </div>
        </footer>
    );
};

export default Footer;
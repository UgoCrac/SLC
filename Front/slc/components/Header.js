import React from 'react';
import 'bootstrap/dist/css/bootstrap.css';
import styles from '../styles/Header.module.css';

function Header() {
    return (
        <div className={`${styles.img} h-100 d-flex`}>
            <div className={`${styles.text} d-flex flex-column align-items-center m-auto`}>
                <h1 className={`${styles.montserrat} text-white text-center pt-5`}>SLC CÔTE D'OPALE</h1>
                <h2 className={`${styles.montserrat} text-white text-center mb-3`}>Entreprise de rénovation spécialisé dans le placot, enduit etc ..</h2>
                <button className={`${styles.btn}`}><a href="#" className='text-decoration-none text-reset'>Nous contacter</a></button>
            </div>
        </div >

    );
};

export default Header;
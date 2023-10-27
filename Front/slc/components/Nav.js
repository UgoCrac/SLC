import React, { useState, useEffect } from 'react';
import Cookies from 'js-cookie';
import 'bootstrap/dist/css/bootstrap.css';
import styles from '../styles/Nav.module.css';
import Link from 'next/link';

function Nav() {
    const [authToken, setAuthToken] = useState(null);

    useEffect(() => {
        // Récupérer le token du cookie
        const token = Cookies.get('auth_token');
        if (token) {
            setAuthToken(token);
        }
    }, []);

    const handleLogout = () => {
        // Effacer le token d'authentification du cookie
        Cookies.remove('auth_token');
        // Mise à jour de l'état de authToken
        setAuthToken(null);
    };

    return (
        <nav className={`${styles.nav} d-flex justify-content-between position-fixed`}>
            <img src="img/logo2.jpg" alt="logo" className='m-2' style={{ width: 100 }} />
            <ul className='list-unstyled d-flex mx-4 align-items-center m-0'>
                <li className='px-4'><Link href="/" className={`${styles.montserrat} text-decoration-none text-reset`}>Accueil</Link></li>
                <li className='px-4'><Link href="#" className={`${styles.montserrat} text-decoration-none text-reset`}>A propos</Link></li>
                <li className='px-4'><Link href="#" className={`${styles.montserrat} text-decoration-none text-reset`}>Réalisations</Link></li>
                <li className='px-4'><Link href="#" className={`${styles.montserrat} text-decoration-none text-reset`}>Contact</Link></li>
                {authToken && (
                    <>
                        <li className='px-4'><Link href="/Dashboard" className={`${styles.montserrat} text-decoration-none text-reset`}>Dashboard</Link></li>
                        <li className='px-4'><button onClick={handleLogout} className={`${styles.montserrat} text-decoration-none text-reset`}>Déconnexion</button></li>
                    </>
                )}
            </ul>
        </nav>
    );
}

export default Nav;
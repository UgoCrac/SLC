import Head from 'next/head';
import Link from 'next/link';
import { useState, useEffect } from 'react';
import Cookies from 'js-cookie';

export default function Home() {
  const [authToken, setAuthToken] = useState(null);

  useEffect(() => {
    // Récupérer le token du cookie
    const token = Cookies.get('auth_token');
    if (token) {
      console.log(token);
      setAuthToken(token);
    }
  }, []); // Hook UseEffect pour modifier le dom en fonction du token
  // Tableau de dépendance vide pour que le code soit executé aprés le premier rendu

  const handleLogout = () => {
    // Effacer le token d'authentification du cookie
    Cookies.remove('auth_token');
    // Mise à jour de l'état de authToken
    setAuthToken(null);
  };

  return (
    <>
      <h1>Test</h1>
      <nav>
        {authToken ? (
          <>
            <li>
              <Link href="clients/showClients">Voir les clients</Link>
            </li>
            <li>
              <Link href="clients/monCompte">Mon compte</Link>
            </li>
            <li>
              <button onClick={handleLogout}>Se déconnecter</button>
            </li>
          </>
        ) : (
          <>
            <li>
              <Link href="/clients/login">Se connecter</Link>
            </li>
            <li>
              <Link href="/clients/signup">Créer un compte</Link>
            </li>
          </>
        )}
      </nav>
    </>
  );
}
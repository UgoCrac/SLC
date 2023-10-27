import Link from "next/link";
import { useState, useEffect } from "react";

export default function ShowClients() {

    const [token, setToken] = useState(null);
    const [clients, setClients] = useState([]);

    useEffect(() => {
        fetch('https://127.0.0.1:8000/api/login_check', {
            method: 'POST',
            headers: {
                'Content-type': 'application/json'
            },
            body: JSON.stringify({
                username: 'admin@api.com',
                password: 'password'
            })
        })
            .then((res) => res.json())
            .then((data) => {
                setToken(data);
                // Une fois que vous avez le token, effectuez la requête pour les clients ici
                fetchClients(data.token);
            })
            .catch((error) => {
                console.error('Erreur lors de la récupération des données de l\'API :', error);
            });
    }, []);

    const fetchClients = (token) => {
        if (token) {
            fetch('https://127.0.0.1:8000/api/clients', {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
                .then((res) => res.json())
                .then((data) => {
                    setClients(data); // Mettez à jour l'état des clients avec les données de l'API
                })
                .catch((error) => {
                    console.error('Erreur lors de la récupération des données de l\'API :', error);
                });
        }
    };

    return (
        <>
            {/* <h1>Token : </h1>
            <p>{token && token.token}</p> */}

            <h2>Liste clients : </h2>
            <ul>
                {clients.map((client, index) => (
                    <li key={index}>{client.nom} - {client.prenom}</li>
                ))}
            </ul>
        </>
    )
}
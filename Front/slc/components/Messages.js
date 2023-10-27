import React, { useState, useEffect } from "react";
import 'bootstrap/dist/css/bootstrap.css'; // Assurez-vous que Bootstrap est correctement inclus
import Cookies from 'js-cookie';

function Messages() {
    const [messages, setMessages] = useState([]);
    const token = Cookies.get('auth_token');

    useEffect(() => {
        if (token) {
            fetch('https://127.0.0.1:8000/api/messages', {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
                .then((res) => {
                    if (res.ok) {
                        return res.json();
                    } else {
                        throw new Error(`Erreur lors de la récupération des données de l'API : ${res.status}`);
                    }
                })
                .then((data) => {
                    setMessages(data);
                })
                .catch((error) => {
                    console.error('Erreur lors de la récupération des données de l\'API :', error);
                });
        }
    }, []);

    // Fonction pour supprimer un message
    const deleteMessage = (messageId) => {
        const updatedMessages = messages.filter(message => message.id !== messageId);
        setMessages(updatedMessages);
    };

    // Fonction pour formater la date au format classique
    const formatClassicDate = (dateString) => {
        const options = { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' };
        return new Date(dateString).toLocaleDateString('fr-FR', options);
    };

    return (
        <div className="container">
            <h1 className="my-4">Boîte de Réception</h1>
            <div className="list-group">
                {messages.map((message, index) => (
                    <div key={index} className="list-group-item list-group-item-action d-flex">
                        <div>
                            <h5 className="mb-1">{message.Nom}</h5>
                            <p className="mb-1">{message.Tel}</p>
                            <p className="mb-1">{message.Adresse}</p>
                            <small className="text-muted">{formatClassicDate(message.date)}</small>
                        </div>
                        <div className="flex-grow-1">
                            <p className="mb-1 ms-5">{message.Message}</p>
                        </div>
                        <div className="text-center m-auto">
                            <button
                                className="btn btn-danger btn-sm"
                                onClick={() => deleteMessage(message.id)}
                            >
                                Supprimer
                            </button>
                        </div>
                    </div>
                ))}
            </div>
        </div>
    )
}

export default Messages;

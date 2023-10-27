import React, { useState, useEffect } from "react";
import 'bootstrap/dist/css/bootstrap.css';
import Cookies from 'js-cookie';

function Clients() {
    const [clients, setClients] = useState([]);
    const token = Cookies.get('auth_token');
    const [addForm, setAddForm] = useState(false);
    const [newClient, setNewClient] = useState({
        nom: "",
        prenom: "",
        adresse: "",
        mail: "",
        telephone: ""
    })

    useEffect(() => {
        if (token) {
            fetch('https://127.0.0.1:8000/api/clients', {
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
                    setClients(data);
                })
                .catch((error) => {
                    console.error('Erreur lors de la récupération des données de l\'API :', error);
                });
        }
    }, []);

    const handleDeleteClient = (idClient) => {
        if (window.confirm("Voulez-vous vraiment supprimer ce client ?")) {
            fetch(`https://127.0.0.1:8000/api/clients/${idClient}`, {
                method: "DELETE",
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
                .then((res) => {
                    if (res.ok) {
                        // Suppression réussie, mettez à jour l'état local en supprimant le client
                        setClients(clients.filter(client => client.id !== idClient));
                    } else {
                        throw new Error(`Erreur lors de la suppression du client : ${res.status}`);
                    }
                })
                .catch((error) => {
                    console.error("Erreur : ", error);
                });
        }
    }

    const handleAddClient = () => {
        setAddForm(true);
    }

    const handleFormChange = (e) => {
        const { name, value } = e.target;
        setNewClient({ ...newClient, [name]: value });
    }

    const handleFormSubmit = (e) => {
        e.preventDefault();
        fetch(`https://127.0.0.1:8000/api/clients`, {
            method: "POST",
            headers: {
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify(newClient)
        })
            .then((res) => {
                if (res.ok) {
                    return res.json();
                } else {
                    throw new Error("Erreur lors de l'ajout du client")
                }
            }).then((data) => {
                setClients([...clients, data]);
                setAddForm(false);
            })
            .catch((error) => {
                console.error("Erreur : ", error);
            });
    }


    return (
        <div>
            <h1 className="text-center mt-3">Liste clients :</h1>
            <table className="table table-striped">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Adresse</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    {clients.map((client, index) => (
                        <tr key={index}>
                            <td>{client.nom}</td>
                            <td>{client.prenom}</td>
                            <td>{client.adresse}</td>
                            <td>{client.mail}</td>
                            <td>{client.telephone}</td>
                            <td>
                                <button
                                    className="btn btn-danger"
                                    onClick={() => handleDeleteClient(client.id)}
                                >
                                    Supprimer
                                </button>
                            </td>
                        </tr>
                    ))}
                </tbody>
            </table>
            <button
                className="btn btn-success"
                onClick={() => handleAddClient()}>Ajouter un nouveau client</button>
            {addForm && (
                <table className="table table-striped">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Adresse</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <input
                                    type="text"
                                    name="nom"
                                    value={newClient.nom}
                                    onChange={handleFormChange}
                                />
                            </td>
                            <td>
                                <input
                                    type="text"
                                    name="prenom"
                                    value={newClient.prenom}
                                    onChange={handleFormChange}
                                />
                            </td>
                            <td>
                                <input
                                    type="text"
                                    name="adresse"
                                    value={newClient.adresse}
                                    onChange={handleFormChange}
                                />
                            </td>
                            <td>
                                <input
                                    type="email"
                                    name="mail"
                                    value={newClient.mail}
                                    onChange={handleFormChange}
                                />
                            </td>
                            <td>
                                <input
                                    type="tel"
                                    name="telephone"
                                    value={newClient.telephone}
                                    onChange={handleFormChange}
                                />
                            </td>
                            <td>
                                <button
                                    type="submit"
                                    className="btn btn-primary"
                                    onClick={handleFormSubmit}
                                >
                                    Ajouter
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            )}
        </div>
    )
}

export default Clients;
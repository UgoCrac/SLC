import React from 'react';
import Form from 'react-bootstrap/Form';
import Button from 'react-bootstrap/Button';
import Container from 'react-bootstrap/Container';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import styles from '../styles/Contact.module.css'
import { useState, useEffect } from "react";

function Contact() {

    const [message, setMessage] = useState([]);
    const [validation, setValidation] = useState("");

    const [formData, setFormData] = useState({
        nom: "",
        tel: "",
        email: "",
        adresse: "",
        message: ""
    }); // State pour stocker les données du formulaire

    const [error, setError] = useState(null);

    const handleChange = (e) => {
        // Actualiser formData avec les infos du formulaire
        const { name, value } = e.target;
        setFormData({
            ...formData, // Copie toute les paires dans un nouvel objet
            [name]: value, // Ajoute une nouvelle paire clé valeur a formData (name et value de mes inputs)
        });
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        // Requête POST pour créer un message avec les données du formulaire
        fetch("https://127.0.0.1:8000/api/messages", {
            method: "POST",
            headers: {
                "Content-Type": "application/json", // Spécifie les headers de la requête HTTP
            },
            body: JSON.stringify(formData), // Spécifie le corps (ici, les paires clé-valeur dans formData)
        })
            .then((res) => res.json())
            .then((data) => {
                setMessage(data); // Mettez à jour l'état des clients avec les données de l'API
                setValidation("Votre message à bien été envoyé !");
                setFormData({
                    nom: "",
                    tel: "",
                    email: "",
                    adresse: "",
                    message: ""
                });
            })
            .catch((error) => {
                console.error('Erreur lors de la récupération des données de l\'API :', error);
            });

    };


    return (
        <div className={`${styles.bg}`}>
            <Container>
                <h3 className={`${styles.montserrat} text-center w-50 m-auto mb-5 mt-5 pt-5`}>Demande de renseignements</h3>
                <Form onSubmit={handleSubmit}>
                    <Row>
                        <Col xs={9} className='w-100 mb-3'>
                            <Form.Group controlId="formName">
                                <Form.Control type="name" placeholder="Nom et prénom*" style={{ width: '100%' }} name="nom" value={formData.nom} onChange={handleChange} required />
                            </Form.Group>
                        </Col>
                    </Row>
                    <Row>
                        <Col className='mb-3'>
                            <Form.Group controlId="formPhone">
                                <Form.Control type="telephone" placeholder="Téléphone*" name="tel" value={formData.tel} onChange={handleChange} required />
                            </Form.Group>
                        </Col>
                        <Col>
                            <Form.Group controlId="formEmail">
                                <Form.Control type="email" placeholder="Email*" name="email" value={formData.email} onChange={handleChange} required />
                            </Form.Group>
                        </Col>
                    </Row>
                    <Row>
                        <Col xs={9} className='w-100 mb-3'>
                            <Form.Group controlId="formAddress">
                                <Form.Control type="text" placeholder="Adresse*" style={{ width: '100%' }} name="adresse" value={formData.adresse} onChange={handleChange} required />
                            </Form.Group>
                        </Col>
                    </Row>
                    <Row>
                        <Col>
                            <Form.Group controlId="formComments">
                                <Form.Control as="textarea" placeholder="Votre message...*" className={`${styles.textArea} mb-3`} name="message" value={formData.message} onChange={handleChange} required />
                            </Form.Group>
                        </Col>
                    </Row>
                    <Row className='mb-5'>
                        <Col className="d-flex justify-content-center">
                            <Button variant="primary" type="submit">
                                Envoyer
                            </Button>
                        </Col>
                    </Row>
                </Form>
                <h2 className='text-success text-center'>{validation}</h2>
            </Container>
        </div>
    );
}


export default Contact;
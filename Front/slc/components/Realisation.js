import React from 'react';
import 'bootstrap/dist/css/bootstrap.css';
import styles from '../styles/Realisation.module.css'

function Realisation() {
    return (
        <div className={`${styles.margin}`}>
            <h1 className={`${styles.montserrat} text-center mb-5`}>Nos réalisations</h1>
            <div id="carouselExampleCaptions" className={`${styles.carousel} carousel slide`} data-bs-ride="false">
                <div className="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" className="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
                <div className="carousel-inner">
                    <div className="carousel-item active">
                        <img src="/img/chantier1.jpg" className={`${styles.img} d-block w-50 m-auto`} alt="..." />
                        <div className="carousel-caption d-flex flex-column h-100 align-items-center justify-content-center">
                            <h2 className="bg-dark bg-opacity-50 py- px-4">Chantier 1</h2>
                            <p className="bg-dark bg-opacity-50 py-2 px-4">Description du premier chantier</p>
                        </div>
                    </div>
                    <div className="carousel-item">
                        <img src="/img/chantier2.jpg" className="d-block w-50 m-auto" alt="..." />
                        <div className="carousel-caption d-flex flex-column h-100 align-items-center justify-content-center">
                            <h2 className="bg-dark bg-opacity-50 py-2 px-4">Chantier 2</h2>
                            <p className="bg-dark bg-opacity-50 py-2 px-4">Description du deuxiéme chantier</p>
                            <a href="#" className="btn btn-outline-light px-4 py-2 rounded-0">Voir plus</a>
                        </div>
                    </div>
                    <div className="carousel-item">
                        <img src="/img/chantier3.jpg" className="d-block w-50 m-auto" alt="..." />
                        <div className="carousel-caption d-flex flex-column h-100 align-items-center justify-content-center">
                            <h2 className="bg-dark bg-opacity-50 py-2 px-4">Chantier 3</h2>
                            <p className="bg-dark bg-opacity-50 py-2 px-4">Description du troisiéme chantier</p>
                            <a href="#" className="btn btn-outline-light px-4 py-2 rounded-0">Learn More</a>
                        </div>
                    </div>
                </div>
                <button className="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                    <span className={`${styles.carouselControlPrevIcon}`} aria-hidden="true"></span>
                    <span className="visually-hidden">Previous</span>
                </button>
                <button className="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                    <span className={`${styles.carouselControlNextIcon}`} aria-hidden="true"></span>
                    <span className="visually-hidden">Next</span>
                </button>
            </div>

        </div>
    );
}

export default Realisation;
import React, { useState } from "react";
import 'bootstrap/dist/css/bootstrap.css';
import styles from '../styles/Dashboard.module.css';
import Clients from '../components/Clients';
import Messages from '../components/Messages';

function Dashboard() {
    const [selectedComponent, setSelectedComponent] = useState(null);

    const showComponent = (component) => {
        setSelectedComponent(component);
    };

    return (
        <div className="d-flex">
            <nav id="sidebar" className={`${styles.bg}`}>
                <div className="sidebar-header">
                    <h3 className="ms-2 mt-2">Mon Dashboard</h3>
                </div>

                <ul className="list-unstyled components">
                    <li className="mt-4 ms-2">
                        <a href="#" className="text-decoration-none text-reset">Accueil</a>
                    </li>
                    <li className="mt-4 ms-2">
                        <a href="#" className="text-decoration-none text-reset" onClick={() => showComponent(<Clients />)}>
                            Mes clients
                        </a>
                    </li>
                    <li className="mt-4 ms-2">
                        <a href="#" className="text-decoration-none text-reset">
                            Mes Matériaux
                        </a>
                    </li>
                    <li className="mt-4 ms-2">
                        <a href="#" className="text-decoration-none text-reset">
                            Mes devis
                        </a>
                    </li>
                    <li className="mt-4 ms-2">
                        <a href="#" className="text-decoration-none text-reset" onClick={() => showComponent(<Messages />)}>
                            Messagerie
                        </a>
                    </li>
                </ul>
            </nav>
            <div className="w-75 mx-auto">
                {selectedComponent}
            </div>
        </div>
    );
}

export default Dashboard;

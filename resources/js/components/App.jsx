import * as React from "react";
import Navbar from "react-bootstrap/Navbar";
import Container from "react-bootstrap/Container";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";
import "bootstrap/dist/css/bootstrap.css";

import { BrowserRouter as Router , Routes, Route, Link } from "react-router-dom";

import EditEvent from "./events/edit.component";
import ListEvent from "./events/list.component";
import CreateEvent from "./events/create.component";

const App = () => {
    return (<Router>
        <Navbar bg="primary">
          <Container>
            <Link to={"/"} className="navbar-brand text-white">
              Event App
            </Link>
          </Container>
        </Navbar>
    
        <Container className="mt-5">
          <Row>
            <Col md={12}>
              <Routes>
                <Route path="/create/event" element={<CreateEvent />} />
                <Route path="/edit/event/:id" element={<EditEvent />} />
                <Route exact path='/' element={<ListEvent />} />
              </Routes>
            </Col>
          </Row>
        </Container>
      </Router>);
}

export default App
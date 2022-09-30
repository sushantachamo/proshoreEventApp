import React, { useState } from "react";
import Form from 'react-bootstrap/Form'
import Button from 'react-bootstrap/Button'
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import axios from 'axios'
import Swal from 'sweetalert2';
import { useNavigate } from 'react-router-dom'
import { isSet } from "lodash";


export default function CreateEvent() {
	const navigate = useNavigate();

	const [title, setTitle] = useState("")
	const [description, setDescription] = useState("")
	const [startDate, setStartDate] = useState("")
	const [endDate, setEndDate] = useState("")
	const [validationError,setValidationError] = useState({})
  const [validationErrorMessage,setValidationErrorMessage] = useState({})

  const CreateEvent = async (e) => {
    e.preventDefault();
    var toastMixin = Swal.mixin({
      toast: true,
      icon: 'success',
      title: 'General Title',
      position: 'top-right',
      showConfirmButton: false,
      timer: 1500,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
      }
    });

    const formData = new FormData()

    formData.append('title', title)
    formData.append('description', description)
	  formData.append('startDate', startDate)
	  formData.append('endDate', endDate)

    let errorMsg = ''

    if(title === '') {
      errorMsg += 'Event Title is Required'+ '\n'
    }

    if(description === '') {
      errorMsg += 'Event Description is Required'+ '\n'
    }

    if(startDate === '') {
      errorMsg += 'Event Start Date is Required'+ '\n'
    }

    if(endDate === '') {
      errorMsg += 'Event End Date is Required'+ '\n'
    }
    if(errorMsg) {
      return toastMixin.fire({
        title: errorMsg,
        icon: 'error'
      });
    }

    

    await axios.post(`http://localhost:8000/api/event`, formData).then(({data})=>{
      Swal.fire({
        icon:"success",
        text:data.message
      })
      navigate("/")
    }).catch(({response})=>{
      if(response.status===422){
        setValidationError(response.data.errors)
      }else{
        
        var errors = '';
        setValidationErrorMessage(response.data.message);
        Object.entries(response.data.message).map(([key, value]) => (errors += value +'\n')) 
        toastMixin.fire({
          title: errors,
          icon: 'error'
        });
        
      }
    })
  }

  return (
    <div className="container">
      <div className="row justify-content-center">
        <div className="col-12 col-sm-12 col-md-6">
          <div className="card">
            <div className="card-body">
              <h4 className="card-title">Create Event</h4>
              <hr />
              <div className="form-wrapper">
                {
                  Object.keys(validationError).length > 0 && (
                    <div className="row">
                      <div className="col-12">
                        <div className="alert alert-danger">
                          <ul className="mb-0">
                            {
                              Object.entries(validationError).map(([key, value])=>(
                                <li key={key}>{value}</li>   
                              ))
                            }
                          </ul>
                        </div>
                      </div>
                    </div>
                  )
                }
                <Form onSubmit={CreateEvent}>
                  <Row> 
                      <Col>
                        <Form.Group controlId="Name">
                            <Form.Label>Title</Form.Label>
                            <Form.Control type="text" value={title} onChange={(event)=>{
                              setTitle(event.target.value)
                            }}/>
                        </Form.Group>
                      </Col>  
                  </Row>
                  <Row className="my-3">
                      <Col>
                        <Form.Group controlId="Description">
                            <Form.Label>Description</Form.Label>
                            <Form.Control as="textarea" rows={3} value={description} onChange={(event)=>{
                              setDescription(event.target.value)
                            }}/>
                        </Form.Group>
                      </Col>
                  </Row>

                  	<Row>
						<Col>
							<Form.Group controlId="startDate">
								<Form.Label>Start Date</Form.Label>
								<Form.Control type="date" value={startDate} onChange={(event)=>{
										setStartDate(event.target.value)
									}}/>
							</Form.Group>
						</Col>
                  	</Row>

					  <Row>
						<Col>
							<Form.Group controlId="endDate">
								<Form.Label>End Date</Form.Label>
								<Form.Control type="date" value={endDate} onChange={(event)=>{
										setEndDate(event.target.value)
									}}/>
							</Form.Group>
						</Col>
                  	</Row>

                  <Button variant="primary" className="mt-2" size="lg" block="block" type="submit">
                    Save
                  </Button>
                </Form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}
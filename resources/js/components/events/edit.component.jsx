import React, { useEffect, useState } from "react";
import Form from 'react-bootstrap/Form'
import Button from 'react-bootstrap/Button';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import { useNavigate, useParams } from 'react-router-dom'
import axios from 'axios';
import Swal from 'sweetalert2';

export default function EditUser() {
  const navigate = useNavigate();

  const { id } = useParams()

  const [title, setTitle] = useState("")
  const [description, setDescription] = useState("")
  const [startDate, setStartDate] = useState("")
  const [endDate, setEndDate] = useState("")
  const [validationError,setValidationError] = useState({})
  const [validationErrorMessage,setValidationErrorMessage] = useState({})


  useEffect(()=>{
    fetchEvent()
  },[])

  const fetchEvent = async () => {
    await axios.get(`http://localhost:8000/api/event/${id}`).then(({data})=>{
      const { title, description, startDate, endDate } = data.data
      setTitle(title)
      setDescription(description)
      setStartDate(startDate)
      setEndDate(endDate)
    }).catch(({response:{data}})=>{
      Swal.fire({
        text:data.message,
        icon:"error"
      })
    })
  }



  const UpdateEvent = async (e) => {
    e.preventDefault();

    const formData = new FormData()
    formData.append('_method', 'PATCH');
    formData.append('title', title)
    formData.append('description', description)
    formData.append('startDate', startDate)
	  formData.append('endDate', endDate)


    await axios.post(`http://localhost:8000/api/event/${id}`, formData).then(({data})=>{
      Swal.fire({
        icon:"success",
        text:data.message
      })
      navigate("/")
    }).catch(({response})=>{
      if(response.status===422){
        setValidationError(response.data.errors)
      }else{
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
              <h4 className="card-title">Update Event</h4>
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
                <Form onSubmit={UpdateEvent}>
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
                    Update
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
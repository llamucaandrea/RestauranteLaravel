<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Experiencia;
use App\GradoEstudio;
use App\ReferenciaPersonal;
use App\Empleado;

class EmpleadoController extends Controller
{
    /** Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$empleado = Empleado::All()->sortByDesc('emp_id');
		session(['I' => 0]);
		session(['REFERENCIA' => null]);
		session(['EXPERIENCIA' => null]);
		session(['ESTUDIO' => null]);
		session(['EMPLEADO' => null]);
		return view('empleado.index', compact('empleado'));
	}
	public function detalleEmpleado($id)
	{
		$empleado = Empleado::where('emp_id',$id)->first();
		$estudio = GradoEstudio::where('emp_id',$id)->get();
		$experiencia = Experiencia::where('emp_id',$id)->get();
		$referencia = ReferenciaPersonal::where('emp_id',$id)->get();	
		return view('empleado.detalleEmpleado', compact('empleado','estudio','experiencia','referencia'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		session(['I' => 0]);
		session(['REFERENCIA' => null]);
		session(['EXPERIENCIA' => null]);
		session(['ESTUDIO' => null]);
		session(['EMPLEADO' => null]);
		return view('empleado.create');
	}
	public function createEmpleado(Request $request)
	{
		$empleado = array(
			'emp_nombre' => $request['emp_nombre'],
			'emp_cedula' => $request['emp_cedula'],
			'emp_direccion' => $request['emp_direccion'],
			'emp_telefono' => $request['emp_telefono'],
			'emp_email' => $request['emp_email'],
			'emp_area_trabajo' => $request['emp_area_trabajo'],
			'emp_comentario' => $request['emp_comentario'],
		);
		session(['EMPLEADO' => $empleado]);
		return view('empleado.createAcademico')
			->with('datos',$empleado);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	 
	public function createAcademico(Request $request)
	{
		$i=session('I');
		$estudio=session('ESTUDIO');
		$empleado=session('EMPLEADO');
		$estudio[$i] = array(
			'est_numero' => $i,
			'gra_est_instituto' => $request['gra_est_instituto'],
			'gra_est_año_estudio' => $request['gra_est_año_estudio'],
			'gra_est_estado' => $request['gra_est_estado'],
		);		
		$i=$i+1;
		session(['ESTUDIO' => $estudio]);
		session(['I' => $i]);
		return view('empleado.createAcademico')
			->with('estudio',$estudio)
			->with('datos',$empleado);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	 
	public function createDatoExperiencia()
	{
		session(['I' => 0]);
		$estudio=session('ESTUDIO');
		$empleado=session('EMPLEADO');		
		return view('empleado.createExperiencia')
			->with('datos',$empleado)
			->with('estudio',$estudio);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	 
	public function createExperiencia(Request $request)
	{
		$i=session('I');
		$estudio=session('ESTUDIO');
		$experiencia=session('EXPERIENCIA');
		$empleado=session('EMPLEADO');
		$experiencia[$i] = array(
			'exp_numero' => $i,
			'exp_empresa' => $request['exp_empresa'],
			'exp_tiempo' => $request['exp_tiempo'],
			'exp_motivo_salida' => $request['exp_motivo_salida'],
			'exp_estado_salida' => $request['exp_estado_salida'],
		);		
		$i=$i+1;
		session(['EXPERIENCIA' => $experiencia]);
		session(['I' => $i]);
		return view('empleado.createExperiencia')
			->with('experiencia',$experiencia)
			->with('datos',$empleado)
			->with('estudio',$estudio);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	 
	public function createDatoReferencia()
	{
		session(['I' => 0]);
		$estudio=session('ESTUDIO');
		$empleado=session('EMPLEADO');	
		$experiencia=session('EXPERIENCIA');
		$empleado=session('EMPLEADO');		
		return view('empleado.createReferencia')
			->with('datos',$empleado)
			->with('experiencia',$experiencia)
			->with('estudio',$estudio);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	 
	public function createReferencia(Request $request)
	{
		$i=session('I');
		$estudio=session('ESTUDIO');
		$referencia=session('REFERENCIA');
		$empleado=session('EMPLEADO');
		$experiencia=session('EXPERIENCIA');
		$referencia[$i] = array(
			'ref_numero' => $i,
			'ref_per_nombre' => $request['ref_per_nombre'],
			'ref_per_telefono' => $request['ref_per_telefono'],
			'ref_per_parentesco' => $request['ref_per_parentesco'],
		);		
		$i=$i+1;
		session(['REFERENCIA' => $referencia]);
		session(['I' => $i]);
		return view('empleado.createReferencia')
			->with('referencia',$referencia)
			->with('experiencia',$experiencia)
			->with('datos',$empleado)
			->with('estudio',$estudio);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{			
		$empleado=session('EMPLEADO');
		$experiencia=session('EXPERIENCIA');
		$estudio=session('ESTUDIO');
		$referencia=session('REFERENCIA');
		if ($request->file('emp_foto')) {
    		$file = $request->file('emp_foto');
	    	$nombre_ima = 'empleado_' . time() . '.' . $file->getClientOriginalExtension();
	    	$path = public_path().'/empleados/';
	    	$file -> move($path, $nombre_ima);
    	}
		Empleado::create([
			'emp_nombre' => $empleado['emp_nombre'],
			'emp_cedula' => $empleado['emp_cedula'],
			'emp_direccion' => $empleado['emp_direccion'],
			'emp_telefono' => $empleado['emp_telefono'],
			'emp_email' => $empleado['emp_email'],
			'emp_area_trabajo' => $empleado['emp_area_trabajo'],
			'emp_comentario' => $empleado['emp_comentario'],
			'emp_foto' => $nombre_ima
		]);	
		$emp_id = Empleado::All();
		$emp_id = $emp_id->last();
		$emp_id = $emp_id->emp_id;
		for($i=0;$i<count($estudio);$i++){
			GradoEstudio::create([
				'gra_est_instituto' => $estudio[$i]['gra_est_instituto'],
				'gra_est_año_estudio' => $estudio[$i]['gra_est_año_estudio'],
				'gra_est_estado' => $estudio[$i]['gra_est_estado'],
				'emp_id' => $emp_id
			]);
		}
		for($i=0;$i<count($experiencia);$i++){
			Experiencia::create([
				'exp_empresa' => $experiencia[$i]['exp_empresa'],
				'exp_tiempo' => $experiencia[$i]['exp_tiempo'],
				'exp_motivo_salida' => $experiencia[$i]['exp_motivo_salida'],
				'exp_estado_salida' => $experiencia[$i]['exp_estado_salida'],
				'emp_id' => $emp_id
			]);
		}
		for($i=0;$i<count($referencia);$i++){
			ReferenciaPersonal::create([
				'ref_per_nombre' => $referencia[$i]['ref_per_nombre'],
				'ref_per_telefono' => $referencia[$i]['ref_per_telefono'],
				'ref_per_parentesco' => $referencia[$i]['ref_per_parentesco'],
				'emp_id' => $emp_id
			]);
		}
		return redirect('empleado')
			->with('title','Empleado creado!')
			->with('subtitle','El registro del empleado se ha realizado con éxito');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	 
	public function createFoto()
	{
		$estudio=session('ESTUDIO');
		$empleado=session('EMPLEADO');	
		$experiencia=session('EXPERIENCIA');
		$referencia=session('REFERENCIA');		
		return view('empleado.createDatos')
			->with('datos',$empleado)
			->with('experiencia',$experiencia)
			->with('referencia',$referencia)
			->with('estudio',$estudio);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	public function getEmpleado()
	{
		$empleado=session('EMPLEADO');
		session(['ESTUDIO' => null]);		
		return view('empleado.create')
			->with('datos',$empleado);
	}

	public function getAcademico()
	{
		$estudio=session('ESTUDIO');
		$empleado=session('EMPLEADO');
		$i=count($estudio);
		session(['I' => $i]);
		session(['EXPERIENCIA' => null]);
		return view('empleado.createAcademico')
			->with('datos',$empleado)
			->with('estudio',$estudio);
	}

	public function getExperiencia()
	{
		$estudio=session('ESTUDIO');
		$empleado=session('EMPLEADO');
		$experiencia=session('EXPERIENCIA');
		session(['REFERENCIA' => null]);
		$i=count($experiencia);
		session(['I' => $i]);
		return view('empleado.createExperiencia')
			->with('datos',$empleado)
			->with('experiencia',$experiencia)
			->with('estudio',$estudio);
	}

	public function getReferencia()
	{
		$estudio=session('ESTUDIO');
		$empleado=session('EMPLEADO');
		$experiencia=session('EXPERIENCIA');
		$referencia=session('REFERENCIA');
		$i=count($referencia);
		session(['I' => $i]);
		return view('empleado.createReferencia')
			->with('datos',$empleado)
			->with('experiencia',$experiencia)
			->with('referencia',$referencia)
			->with('estudio',$estudio);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request)
	{
		  
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		
	}
}

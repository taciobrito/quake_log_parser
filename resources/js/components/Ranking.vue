<template>
	<div class="container">
		<header>
			<h2>{{ titulo }}</h2>
		</header>
		
		<section>
			<div class="search">
				<input type="text" class="form-control" placeholder="Buscar por nome">
				<button type="submit" class="btn btn-secondary btn-flat">Buscar</button>
			</div>

			<div class="list">
				<table class="table table-stripped table-hover">
					<thead>
						<tr>
							<th>Name</th>
							<th>
								<img src="/svg/poison.svg" class="poison">
								Kills
							</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(kill, idx) in kills" :key="idx">
							<td>{{ kill.name }}</td>
							<td>{{ kill.kills }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</section>

	</div>
</template>

<script>
	export default {
		name: 'ranking',
		data() {
			return {
				titulo: 'Ranking',
				kills: [
					{ name: 'Udaectus Verum Quiel', kills: 70 },
					{ name: 'Udaectus', kills: 23 },
					{ name: 'Verum', kills: 12 },
					{ name: 'Quiel', kills: -1 },
				],
			}
		},
	}
</script>

<style lang="sass">
	body
		font-family: 'Roboto', sans-serif
</style>